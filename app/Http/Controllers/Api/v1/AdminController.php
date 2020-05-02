<?php

namespace App\Http\Controllers\Api\v1;

use App\Admin;
use App\HistoryAksiDataAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Create a new AdminController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.api:api_v1_admin');
    }

    /**
     * Generate ID untuk admin baru
     * Berdasarkan format tahun-bulan-tanggal-lahir/tahun-masuk/jeniskelamin/indeks(3digit)
     *
     * @param string $tgl_lahir
     * @param string $jenis_kelamin
     * @return string
     */
    private function generateID($tgl_lahir, $jenis_kelamin)
    {
        // Dapatkan format
        $id_format = str_replace('-', '', $tgl_lahir) . date('Y') . ($jenis_kelamin == 'laki' ? '1' : '0');
        // Dapatkan indeks id berdasarkan jumlah format id yang sama + 1
        $indeks_id = sprintf("%'.03d", Admin::where('id', 'REGEXP', '^' . $id_format)->count() + 1);

        return $id_format . $indeks_id;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Authorisasi
        $this->authorize('create', Admin::class);

        $respons_obj = new ResponseObject();

        $validator = Validator::make($request->all(), [
            'password' => ['bail', 'required', 'min:8'],
            're_password' => ['bail', 'required', 'same:password'],
            'nomor_ktp' => ['bail', 'required', 'max:20'],
            'nama' => ['bail', 'required', 'max:50'],
            'tempat_lahir' => ['bail', 'required', 'max:25'],
            'tanggal_lahir' => ['bail', 'required', 'date', 'date_format:Y-m-d'],
            'jenis_kelamin' => ['bail', 'required', 'in:laki,perempuan'],
            'alamat' => ['bail', 'required'],
            'nomor_telpon' => ['bail', 'required', 'max:15'],
            'email' => ['bail', 'required', 'email:rfc,dns'],
            'file_foto' => ['bail', 'file', 'nullable', 'max:1024', 'mimes:jpeg,png,jpg']
        ]);

        if ($validator->fails()) {
            // Gagal validasi
            $respons_obj->status = $respons_obj::STATUS_FAIL;
            $respons_obj->kode = $respons_obj::CODE_BAD_REQUEST;
            $respons_obj->pesan = [
                'error' => collect($validator->errors()->messages())->map(function ($item, $key) {
                    return $item[0];
                }),
            ];
        } else {
            // Validasi berhasil
            // Tambahkan id pada request
            $request->request->add(['id' => $this->generateID($request->tanggal_lahir, $request->jenis_kelamin)]);
            // Hashing password
            $request->request->set('password', Hash::make($request->password));

            // Cek file_foto
            // Jika ada file_foto yang diberikan maka gunakan fot tersebut
            if ($request->hasFile('file_foto')) {
                // Tambahkan foto pada request yang berisi nama file_foto
                $nama_foto = config('fileupload.img.admin_profile.prefix') . $request->id . '-' . time() . '.' . $request->file_foto->getClientOriginalExtension();
                $request->request->add(['foto' => $nama_foto]);
            } else {
                $request->request->add(['foto' => 'profiledefault.png']);
            }

            DB::transaction(function () use ($request) {
                // Insert data admin baru
                $admin = Admin::create($request->only(['id', 'password', 'nomor_ktp', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'nomor_telpon', 'email', 'foto']));

                // Simpan file_foto
                if ($request->hasFile('file_foto')) {
                    $request->file_foto->storeAs(config('fileupload.img.admin_profile.path'), $request->foto);
                }

                // Logging aksi perubahan data
                HistoryAksiDataAdmin::create([
                    'admin' => $admin->id,
                    'pembuat' => auth()->id(),
                    'catatan_aksi' => 'Menambahkan admin baru',
                ]);
            });

            // Objek respon
            $respons_obj->status = $respons_obj::STATUS_OK;
            $respons_obj->kode = $respons_obj::CODE_OK;
            $respons_obj->hasil = [
                'data' => [
                    'new_admin' => $request->only(['id', 'nomor_ktp', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'nomor_telpon', 'email', 'foto']),
                ],
                'next_request_token' => auth()->refresh(),
            ];
        }

        return response()->json($respons_obj, $respons_obj->kode);
    }

    /**
     * Upadate password current admin
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $respons_obj = new ResponseObject();

        $validator = Validator::make($request->all(), [
            'old_password' => [
                'bail', 'required',
                function ($attribute, $value, $fail) use ($request) {
                    // Validasi input password lama harus sesuai dengan yang ada di database
                    if (!Hash::check($request->old_password, auth()->user()->password)) {
                        $fail('Password lama salah');
                    }
                },
            ],
            'new_password' => ['bail', 'required', 'min:8'],
            're_password' => ['bail', 'required', 'same:new_password'],
        ]);

        if ($validator->fails()) {
            // Gagal validasi
            $respons_obj->status = $respons_obj::STATUS_FAIL;
            $respons_obj->kode = $respons_obj::CODE_BAD_REQUEST;
            $respons_obj->pesan = [
                'error' => collect($validator->errors()->messages())->map(function ($item, $key) {
                    return $item[0];
                }),
            ];
        } else {

            // Perbarui password
            $user = auth()->user();
            $user->password = Hash::make($request->new_password);
            $user->save();

            $respons_obj->status = $respons_obj::STATUS_OK;
            $respons_obj->kode = $respons_obj::CODE_OK;
            $respons_obj->hasil = [
                'next_request_token' => auth()->refresh(),
            ];
        }

        return response()->json($respons_obj, $respons_obj->kode);
    }
}
