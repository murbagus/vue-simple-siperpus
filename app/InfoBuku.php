<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoBuku extends Model
{
    protected $table = 'info_buku';
    protected $primaryKey = 'isbn';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['isbn', 'judul', 'pengarang', 'penerbit', 'tahun_terbit'];

    public function f_dataBerubah()
    {
        return $this->hasMany('App\HistoryAksiDataInfoBuku', 'isbn', 'isbn');
    }

    public function f_fisikBuku()
    {
        return $this->hasMany('App\Buku', 'isbn', 'isbn');
    }
}
