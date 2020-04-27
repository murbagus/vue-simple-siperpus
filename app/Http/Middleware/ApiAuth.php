<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\v1\ResponseObject;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $response_obj = new ResponseObject();

        if ($guard != null)
            auth()->shouldUse($guard);

        // Periksa apakah token berlaku/valid/ada
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            // Token sudah tidak berlaku
            $response_obj->status = $response_obj::STATUS_FAIL;
            $response_obj->kode = $response_obj::CODE_UNAUTHORIZED;
            $response_obj->pesan = [
                'error' => 'Token sudah tidak berlaku',
            ];

            return response()->json($response_obj);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            // Token invalid
            $response_obj->status = $response_obj::STATUS_FAIL;
            $response_obj->kode = $response_obj::CODE_UNAUTHORIZED;
            $response_obj->pesan = [
                'error' => 'Token tidak valid',
            ];

            return response()->json($response_obj);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Terjadi kesalahan saat parsing token
            $response_obj->status = $response_obj::STATUS_FAIL;
            $response_obj->kode = $response_obj::CODE_BAD_REQUEST;
            $response_obj->pesan = [
                'error' => 'Request membutuhkan token untuk autentikasi',
            ];

            return response()->json($response_obj);
        }

        return $next($request);
    }
}
