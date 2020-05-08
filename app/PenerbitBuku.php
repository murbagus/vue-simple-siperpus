<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenerbitBuku extends Model
{
    protected $table = 'penerbit_buku';
    public $timestamps = false;

    protected $fillable = ['nama', 'nomor_telpon', 'alamat'];

    public function f_infoBuku()
    {
        return $this->hasMany('App\InfoBuku', 'penerbit', 'id');
    }
}
