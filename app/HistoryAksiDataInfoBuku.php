<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryAksiDataInfoBuku extends Model
{
    protected $table = 'history_aksi_data_info_buku';
    const CREATED_AT = 'waktu_terjadi';
    const UPDATED_AT = null;

    protected $fillable = ['isbn', 'pembuat', 'catatan_aksi'];

    public function f_infoBuku()
    {
        return $this->belongsTo('App\InfoBuku', 'isbn', 'isbn');
    }

    public function f_pembuat()
    {
        return $this->belongsTo('App\Admin', 'pembuat', 'id');
    }
}
