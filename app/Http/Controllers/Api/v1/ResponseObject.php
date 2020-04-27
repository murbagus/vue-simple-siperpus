<?php

namespace App\Http\Controllers\Api\v1;

class ResponseObject
{
    const STATUS_OK = "ok";
    const STATUS_FAIL = "fail";
    const CODE_OK = 200;
    const CODE_CREATED = 201;
    const CODE_BAD_REQUEST = 400;
    const CODE_UNAUTHORIZED = 401;
    const CODE_FORBIDDEN = 403;
    const CODE_NOT_FOUND = 404;
    const CODE_SERVER_ERROR = 500;

    public $status;
    public $kode;
    public $pesan = array();
    public $hasil = array();
}
