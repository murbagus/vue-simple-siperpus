<?php

namespace App\Policies;

use App\InfoBuku;
use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class InfoBukuPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the Admin can view any models.
     *
     * @param  \App\Admin  $user
     * @return mixed
     */
    public function viewAny(Admin $user)
    {
        //
    }

    /**
     * Determine whether the Admin can view the model.
     *
     * @param  \App\Admin  $user
     * @param  \App\InfoBuku  $infoBuku
     * @return mixed
     */
    public function view(Admin $user, InfoBuku $infoBuku)
    {
        //
    }

    /**
     * Determine whether the Admin can create models.
     *
     * @param  \App\Admin  $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return $user->f_memilikiRule->contains(function ($item, $key) {
            return $item->rule == 3;
        });
    }

    /**
     * Determine whether the Admin can update the model.
     *
     * @param  \App\Admin  $user
     * @param  \App\InfoBuku  $infoBuku
     * @return mixed
     */
    public function update(Admin $user, InfoBuku $infoBuku)
    {
        return $user->f_memilikiRule->contains(function ($item, $key) {
            return $item->rule == 3;
        });
    }

    /**
     * Determine whether the Admin can delete the model.
     *
     * @param  \App\Admin  $user
     * @param  \App\InfoBuku  $infoBuku
     * @return mixed
     */
    public function delete(Admin $user, InfoBuku $infoBuku)
    {
        //
    }

    /**
     * Determine whether the Admin can restore the model.
     *
     * @param  \App\Admin  $user
     * @param  \App\InfoBuku  $infoBuku
     * @return mixed
     */
    public function restore(Admin $user, InfoBuku $infoBuku)
    {
        //
    }

    /**
     * Determine whether the Admin can permanently delete the model.
     *
     * @param  \App\Admin  $user
     * @param  \App\InfoBuku  $infoBuku
     * @return mixed
     */
    public function forceDelete(Admin $user, InfoBuku $infoBuku)
    {
        //
    }
}
