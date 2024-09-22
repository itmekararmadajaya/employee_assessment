<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\EmployeeAssessedResponseText;
use App\Models\User;

class EmployeeAssessedResponseTextPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any EmployeeAssessedResponseText');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EmployeeAssessedResponseText $employeeassessedresponsetext): bool
    {
        return $user->checkPermissionTo('view EmployeeAssessedResponseText');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create EmployeeAssessedResponseText');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EmployeeAssessedResponseText $employeeassessedresponsetext): bool
    {
        return $user->checkPermissionTo('update EmployeeAssessedResponseText');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EmployeeAssessedResponseText $employeeassessedresponsetext): bool
    {
        return $user->checkPermissionTo('delete EmployeeAssessedResponseText');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EmployeeAssessedResponseText $employeeassessedresponsetext): bool
    {
        return $user->checkPermissionTo('restore EmployeeAssessedResponseText');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EmployeeAssessedResponseText $employeeassessedresponsetext): bool
    {
        return $user->checkPermissionTo('force-delete EmployeeAssessedResponseText');
    }
}
