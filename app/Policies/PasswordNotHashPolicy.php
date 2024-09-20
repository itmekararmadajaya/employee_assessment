<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PasswordNotHash;
use App\Models\User;

class PasswordNotHashPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PasswordNotHash');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PasswordNotHash $passwordnothash): bool
    {
        return $user->checkPermissionTo('view PasswordNotHash');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PasswordNotHash');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PasswordNotHash $passwordnothash): bool
    {
        return $user->checkPermissionTo('update PasswordNotHash');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PasswordNotHash $passwordnothash): bool
    {
        return $user->checkPermissionTo('delete PasswordNotHash');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PasswordNotHash $passwordnothash): bool
    {
        return $user->checkPermissionTo('restore PasswordNotHash');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PasswordNotHash $passwordnothash): bool
    {
        return $user->checkPermissionTo('force-delete PasswordNotHash');
    }
}
