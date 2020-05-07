<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    protected $keyType = 'string';
    public $incrementing = false;
    const UPDATED_AT = null;

    public function f_infoBuku()
    {
        return $this->belongsTo('App\InfoBuku', 'isbn', 'isbn');
    }
}
