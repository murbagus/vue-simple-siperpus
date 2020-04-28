<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $this->middleware('auth.api:api_v1_admin', ['except' => ['login']]);
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

        return response()->json($respons_obj, $respons_obj->kode);
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

        return response()->json($respons_obj, $respons_obj->kode);
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

        return response()->json($respons_obj, $respons_obj->kode);
    }
}
