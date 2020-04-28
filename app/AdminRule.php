<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminRule extends Model
{
    protected $table = 'admin_rule';
    public $timestamps = false;

    protected $fillable = ['admin', 'rule'];

    public function f_rule()
    {
        return $this->belongsTo('App\Rule', 'rule', 'id');
    }

    public function f_admin()
    {
        return $this->belongsTo('App\Admin', 'admin', 'id');
    }
}
