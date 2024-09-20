<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Departement;
use App\Models\User;

class DepartementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Departement');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Departement $departement): bool
    {
        return $user->checkPermissionTo('view Departement');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Departement');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Departement $departement): bool
    {
        return $user->checkPermissionTo('update Departement');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Departement $departement): bool
    {
        return $user->checkPermissionTo('delete Departement');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Departement $departement): bool
    {
        return $user->checkPermissionTo('restore Departement');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Departement $departement): bool
    {
        return $user->checkPermissionTo('force-delete Departement');
    }
}
