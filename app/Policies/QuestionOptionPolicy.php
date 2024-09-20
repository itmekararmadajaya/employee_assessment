<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\QuestionOption;
use App\Models\User;

class QuestionOptionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any QuestionOption');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QuestionOption $questionoption): bool
    {
        return $user->checkPermissionTo('view QuestionOption');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create QuestionOption');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, QuestionOption $questionoption): bool
    {
        return $user->checkPermissionTo('update QuestionOption');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QuestionOption $questionoption): bool
    {
        return $user->checkPermissionTo('delete QuestionOption');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, QuestionOption $questionoption): bool
    {
        return $user->checkPermissionTo('restore QuestionOption');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, QuestionOption $questionoption): bool
    {
        return $user->checkPermissionTo('force-delete QuestionOption');
    }
}
