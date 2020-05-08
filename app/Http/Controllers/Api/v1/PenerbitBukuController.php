<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\PenerbitBuku;
use Illuminate\Http\Request;

class PenerbitBukuController extends Controller
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
        $respons_obj = new ResponseObject();

        $penerbit_buku = PenerbitBuku::all();

        // Objek respon
        $respons_obj->status = $respons_obj::STATUS_OK;
        $respons_obj->kode = $respons_obj::CODE_OK;
        $respons_obj->hasil = [
            'data' => [
                'penerbit' => $penerbit_buku,
            ],
            'next_request_token' => auth()->refresh(),
        ];

        return response()->json($respons_obj, $respons_obj->kode);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PenerbitBuku  $penerbit_buku
     * @return \Illuminate\Http\Response
     */
    public function show(PenerbitBuku $penerbit_buku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PenerbitBuku  $penerbit_buku
     * @return \Illuminate\Http\Response
     */
    public function edit(PenerbitBuku $penerbit_buku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PenerbitBuku  $penerbit_buku
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PenerbitBuku $penerbit_buku)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PenerbitBuku  $penerbit_buku
     * @return \Illuminate\Http\Response
     */
    public function destroy(PenerbitBuku $penerbit_buku)
    {
        //
    }
}
