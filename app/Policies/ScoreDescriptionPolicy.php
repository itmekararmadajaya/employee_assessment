<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ScoreDescription;
use App\Models\User;

class ScoreDescriptionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ScoreDescription');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ScoreDescription $scoredescription): bool
    {
        return $user->checkPermissionTo('view ScoreDescription');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ScoreDescription');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ScoreDescription $scoredescription): bool
    {
        return $user->checkPermissionTo('update ScoreDescription');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ScoreDescription $scoredescription): bool
    {
        return $user->checkPermissionTo('delete ScoreDescription');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ScoreDescription $scoredescription): bool
    {
        return $user->checkPermissionTo('restore ScoreDescription');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ScoreDescription $scoredescription): bool
    {
        return $user->checkPermissionTo('force-delete ScoreDescription');
    }
}
