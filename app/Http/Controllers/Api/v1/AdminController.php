<?php

namespace App\Http\Controllers\Api\v1;

use App\Admin;
use App\HistoryAksiDataAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
     * Berdasarkan format tahun-bulan-masuk/jeniskelamin/indeks(4digit)
     *
     * @param string $jenis_kelamin
     * @return string
     */
    private function generateID($jenis_kelamin)
    {
        // Menentukan jumlah digit indeks (contoh: 0001 -> memiliki jumlah digit 4)
        $digit_indeks = 4;

        // Dapatkan format
        $id_format = date('Ym') . ($jenis_kelamin == 'laki' ? '1' : '0');
        // Dapatkan indeks tebaru
        $admin_terbaru = Admin::where('id', 'REGEXP', '^' . $id_format)->orderBy('id', 'desc')->first();
        // Jika ada admin yang memiliki id format serupa
        if ($admin_terbaru) {
            // Dapatkan indeks selanjutnya dengan mengambil potongan string id_terbaru untuk mengambil indeks dari id tersebut
            $indeks_selanjutnya = sprintf("%'.0" . $digit_indeks . "d", intval(substr($admin_terbaru->id, 7), 10) + 1);
        } else {
            // Belum ada admin dengan id format serupa
            $indeks_selanjutnya = sprintf("%'.0" . $digit_indeks . "d", 1);
        }

        return $id_format . $indeks_selanjutnya;
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
            DB::transaction(function () use ($request) {
                // Kunci table admin agar tidak dapat di akses oleh session lain saat transaksi ini berlangsung
                // Hal ini berguna agar pengambilan indeks id menjadi sesuai
                DB::table('admin')->lockForUpdate()->get();

                // Tambahkan id pada request
                $request->request->add(['id' => $this->generateID($request->jenis_kelamin)]);
                // Hashing password
                $request->request->set('password', Hash::make($request->password));

                // Cek file_foto
                // Jika ada file_foto yang diberikan maka gunakan fot tersebut
                if ($request->hasFile('file_foto')) {
                    // Tambahkan foto pada request yang berisi nama file_foto
                    $nama_foto = config('fileupload.img.admin_profile.prefix') . $request->id . '-' . time() . '.' . $request->file_foto->getClientOriginalExtension();
                    $request->request->add(['foto' => $nama_foto]);
                } else {
                    $request->request->add(['foto' => config('fileupload.img.admin_profile.default_name')]);
                }

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

    public function update(Request $request, Admin $admin)
    {
        // Authorisasi
        $this->authorize('update', $admin);

        $respons_obj = new ResponseObject();

        $validator = Validator::make($request->all(), [
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
            // Berhasil validasi
            // Cek file_foto
            // Jika ada file_foto yang diberikan maka gunakan foto tersebut
            if ($request->hasFile('file_foto')) {
                // Hapus file foto lama
                $nama_foto_lama = $admin->foto;
                // Jika foto bukan foto default maka hapus file foto tersebut
                if ($nama_foto_lama != config('fileupload.img.admin_profile.default_name')) {
                    Storage::delete(config('fileupload.img.admin_profile.path') . '/' . $nama_foto_lama);
                }

                // Tambahkan foto baru pada request yang berisi nama file_foto
                // Nama foto disini merupakan nama foto yang baru di upload
                $nama_foto = config('fileupload.img.admin_profile.prefix') . $admin->id . '-' . time() . '.' . $request->file_foto->getClientOriginalExtension();
                $request->request->add(['foto' => $nama_foto]);
            }

            DB::transaction(function () use ($admin, $request) {
                // Kunci table admin agar tidak dapat di akses oleh session lain saat transaksi ini berlangsung
                // Hal ini berguna agar proses update tidak terganggu
                DB::table('admin')->lockForUpdate()->get();

                // Update data admin
                $admin->fill($request->only(['nomor_ktp', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'nomor_telpon', 'email', 'foto']));
                $admin->save();

                // Simpan file_foto
                if ($request->hasFile('file_foto')) {
                    $request->file_foto->storeAs(config('fileupload.img.admin_profile.path'), $request->foto);
                }

                // Logging aksi perubahan data
                HistoryAksiDataAdmin::create([
                    'admin' => $admin->id,
                    'pembuat' => auth()->id(),
                    'catatan_aksi' => 'Merubah detail admin',
                ]);
            });

            // Objek respon
            $respons_obj->status = $respons_obj::STATUS_OK;
            $respons_obj->kode = $respons_obj::CODE_OK;
            $respons_obj->hasil = [
                'data' => [
                    'admin' => $admin
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
