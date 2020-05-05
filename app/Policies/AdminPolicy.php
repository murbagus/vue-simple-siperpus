<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Admin $user
     * @return mixed
     */
    public function viewAny(Admin $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Admin $user
     * @param  \App\Admin  $admin
     * @return mixed
     */
    public function view(Admin $user, Admin $admin)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Admin $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return $user->f_memilikiRule->contains(function ($item, $key) {
            return $item->rule == 1;
        });
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Admin $user
     * @param  \App\Admin  $admin
     * @return mixed
     */
    public function update(Admin $user, Admin $admin)
    {
        return $user->f_memilikiRule->contains(function ($item, $key) {
            return $item->rule == 1;
        });
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Admin $user
     * @param  \App\Admin  $admin
     * @return mixed
     */
    public function delete(Admin $user, Admin $admin)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Admin $user
     * @param  \App\Admin  $admin
     * @return mixed
     */
    public function restore(Admin $user, Admin $admin)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Admin $user
     * @param  \App\Admin  $admin
     * @return mixed
     */
    public function forceDelete(Admin $user, Admin $admin)
    {
        //
    }
}
