<?php

namespace App\Http\Controllers\Api\v1;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.api:api_v1_admin', ['except' => ['login', 'register']]);
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
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $respons_obj = new ResponseObject();

        $validator = Validator::make($request->only(['id', 'password']), [
            'id' => 'bail|required',
            'password' => 'bail|required',
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
            $credentials = $request->only(['id', 'password']);

            if (!$token = auth('api_v1_admin')->attempt($credentials)) {
                // Gagal autentikasi
                $respons_obj->status = $respons_obj::STATUS_FAIL;
                $respons_obj->kode = $respons_obj::CODE_UNAUTHORIZED;
                $respons_obj->pesan = [
                    'error' => 'ID atau Password salah',
                ];
            }

            // Berhasil autentikasi
            $respons_obj->status = $respons_obj::STATUS_OK;
            $respons_obj->kode = $respons_obj::CODE_OK;
            $respons_obj->hasil = [
                'data' => [
                    'akses_token' => $token,
                    'tipe_token' => 'bearer',
                    'berlaku_dalam' => [
                        'waktu' => auth('api_v1_admin')->factory()->getTTL(),
                        'satuan' => 'menit',
                    ]
                ]
            ];
        }

        return response()->json($respons_obj);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $respons_obj = new ResponseObject();

        // Berhasil
        $respons_obj->status = $respons_obj::STATUS_OK;
        $respons_obj->kode = $respons_obj::CODE_OK;
        $respons_obj->hasil = [
            'data' => auth()->user(),
            'next_request_token' => auth()->refresh(),
        ];

        return response()->json($respons_obj);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        $respons_obj = new ResponseObject();

        // Berhasil
        $respons_obj->status = $respons_obj::STATUS_OK;
        $respons_obj->kode = $respons_obj::CODE_OK;
        $respons_obj->pesan = [
            'sukses' => 'Berhasil logout',
        ];

        return response()->json($respons_obj);
    }

    public function register(Request $request)
    {
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
            'foto' => ['bail', 'required']
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

            // Insert data
            Admin::create($request->only(['id', 'password', 'nomor_ktp', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'nomor_telpon', 'email', 'foto']));

            // Objek respon
            $respons_obj->status = $respons_obj::STATUS_OK;
            $respons_obj->kode = $respons_obj::CODE_OK;
            $respons_obj->hasil = [
                'data' => [
                    'new_admin' => $request->only(['id', 'nomor_ktp', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'nomor_telpon', 'email', 'foto']),
                ],
            ];
        }

        return response()->json($respons_obj);
    }
}
