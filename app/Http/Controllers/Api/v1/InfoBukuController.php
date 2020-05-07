<?php

namespace App\Http\Controllers\Api\v1;

use App\HistoryAksiDataInfoBuku;
use App\Http\Controllers\Controller;
use App\InfoBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InfoBukuController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', InfoBuku::class);

        $respons_obj = new ResponseObject();

        $validator = Validator::make($request->all(), [
            'isbn' => ['bail', 'required', 'unique:info_buku,isbn'],
            'judul' => ['bail', 'required', 'max:150'],
            'pengarang' => ['bail', 'required', 'max:100'],
            'penerbit' => ['bail', 'required', 'max:50'],
            'tahun_terbit' => ['bail', 'required', 'date_format:Y'],
            'jumlah_buku' => ['bail', 'required', 'integer', 'min:1', 'max:999']
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
            // Validasi berhasil

            // variable untuk menampung data info fisik buku yang terbuat (termasuk id)
            $fisik_buku = [];
            DB::transaction(function () use ($request, &$fisik_buku) {
                // Lock table buku
                DB::table('buku')->lockForUpdate()->get();

                // Simpan data info buku ke database
                $info_buku = InfoBuku::create($request->only(['isbn', 'judul', 'pengarang', 'penerbit', 'tahun_terbit']));

                // Simpan data setiap kode fisik buku ke database
                // Membuat kode unik setiap fisik buku fotmat (timestapm-3digitangka)
                // Iterasi banyaknya kode yang dibuat sebanyak jumlah buku yang ditentukan
                $c_timestamp = time();
                for ($i = 1; $i <= intval($request->jumlah_buku); $i++) {
                    // Generate
                    $fisik_buku = [...$fisik_buku, ['id' => $c_timestamp . sprintf("%'.03d", $i), 'isbn' => $info_buku->isbn, 'created_at' => date('Y-m-d')]];
                }
                DB::table('buku')->insert($fisik_buku);

                // Logging aksi perubahan data
                HistoryAksiDataInfoBuku::create([
                    'isbn' => $info_buku->isbn,
                    'pembuat' => auth()->id(),
                    'catatan_aksi' => 'Menambahkan info buku baru',
                ]);
            });

            // Objek respon
            $respons_obj->status = $respons_obj::STATUS_OK;
            $respons_obj->kode = $respons_obj::CODE_OK;
            $respons_obj->hasil = [
                'data' => [
                    'info_buku' => array_merge($request->only(['isbn', 'judul', 'pengarang', 'penerbit', 'tahun_terbit']), [
                        'fisik_buku' => [
                            'jumlah' => $request->jumlah_buku,
                            'id' => collect($fisik_buku)->map(function ($item, $key) {
                                return $item['id'];
                            })->all(),
                        ]
                    ]),
                ],
                'next_request_token' => auth()->refresh(),
            ];
        }

        return response()->json($respons_obj, $respons_obj->kode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InfoBuku  $info_buku
     * @return \Illuminate\Http\Response
     */
    public function show(InfoBuku $info_buku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InfoBuku  $info_buku
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InfoBuku $info_buku)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InfoBuku  $info_buku
     * @return \Illuminate\Http\Response
     */
    public function destroy(InfoBuku $info_buku)
    {
        //
    }
}
