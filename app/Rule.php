<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rule';

    public function f_adminRule()
    {
        $this->hasMany('App\AdminRule', 'rule', 'id');
    }
}
