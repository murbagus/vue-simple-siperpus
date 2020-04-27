<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryAksiDataAdmin extends Model
{
    protected $table = 'history_aksi_data_admin';
    const CREATED_AT = 'waktu_terjadi';
    const UPDATED_AT = null;

    protected $fillable = ['admin', 'pembuat', 'catatan_aksi'];

    public function f_admin()
    {
        return $this->belongsTo('App\Admin', 'admin', 'id');
    }

    public function f_pembuat()
    {
        return $this->belongsTo('App\Admin', 'pembuat', 'id');
    }
}
