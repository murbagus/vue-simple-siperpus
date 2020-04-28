<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminRulePolicy
{
    use HandlesAuthorization;

    public function set(Admin $user)
    {
        return $user->f_memilikiRule->contains(function ($item, $key) {
            return $item->rule == 1;
        });
    }
}
