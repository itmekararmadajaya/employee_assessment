<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\QuestionLevel;
use App\Models\User;

class QuestionLevelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any QuestionLevel');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QuestionLevel $questionlevel): bool
    {
        return $user->checkPermissionTo('view QuestionLevel');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create QuestionLevel');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, QuestionLevel $questionlevel): bool
    {
        return $user->checkPermissionTo('update QuestionLevel');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QuestionLevel $questionlevel): bool
    {
        return $user->checkPermissionTo('delete QuestionLevel');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, QuestionLevel $questionlevel): bool
    {
        return $user->checkPermissionTo('restore QuestionLevel');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, QuestionLevel $questionlevel): bool
    {
        return $user->checkPermissionTo('force-delete QuestionLevel');
    }
}
