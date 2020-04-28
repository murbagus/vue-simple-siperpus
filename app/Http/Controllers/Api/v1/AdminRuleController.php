<?php

namespace App\Http\Controllers\Api\v1;

use App\AdminRule;
use App\HistoryAksiDataAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminRuleController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function set(Request $request)
    {
        $respons_obj = new ResponseObject();

        $validator = Validator::make($request->all(), [
            'admin' => ['bail', 'required', 'exists:admin,id'],
            'rule.*' => ['bail', 'required', 'exists:rule,id'],
        ]);

        if ($validator->fails()) {
            // Validasi gagal
            $respons_obj->status = $respons_obj::STATUS_FAIL;
            $respons_obj->kode = $respons_obj::CODE_BAD_REQUEST;
            $respons_obj->pesan = [
                'error' => collect($validator->errors()->messages())->map(function ($item, $key) {
                    return $item[0];
                }),
            ];
        } else {
            // Periksa apakah ada rule yang diberikan
            if ($request->rule) {
                DB::transaction(function () use ($request) {
                    // Hapus semua rule yang sebelumnya sudah diberikan kepada admin
                    DB::table('admin_rule')->where('admin', $request->admin)->delete();

                    // Berikan rule pada admin sesuai request
                    foreach ($request->rule as $rule) {
                        AdminRule::create(['admin' => $request->admin, 'rule' => $rule]);
                    }

                    // Logging aksi perubahan data
                    HistoryAksiDataAdmin::create([
                        'admin' => $request->admin,
                        'pembuat' => auth()->id(),
                        'catatan_aksi' => 'Merubah rule admin',
                    ]);
                });

                $respons_obj->status = $respons_obj::STATUS_OK;
                $respons_obj->kode = $respons_obj::CODE_OK;
                $respons_obj->hasil = [
                    'data' => [
                        'next_request_token' => auth()->refresh(),
                    ]
                ];
            } else {
                // Tidak ada rule yang diberikan
                $respons_obj->status = $respons_obj::STATUS_FAIL;
                $respons_obj->kode = $respons_obj::CODE_BAD_REQUEST;
                $respons_obj->pesan = [
                    'error' => 'Tidak ada rule yang diberikan'
                ];
            }
        }

        return response()->json($respons_obj);
    }
}
