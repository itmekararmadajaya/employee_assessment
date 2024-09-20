<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Assessor;
use App\Models\User;

class AssessorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Assessor');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Assessor $assessor): bool
    {
        return $user->checkPermissionTo('view Assessor');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Assessor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Assessor $assessor): bool
    {
        return $user->checkPermissionTo('update Assessor');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Assessor $assessor): bool
    {
        return $user->checkPermissionTo('delete Assessor');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Assessor $assessor): bool
    {
        return $user->checkPermissionTo('restore Assessor');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Assessor $assessor): bool
    {
        return $user->checkPermissionTo('force-delete Assessor');
    }
}
