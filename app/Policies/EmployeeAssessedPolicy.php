<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\EmployeeAssessed;
use App\Models\User;

class EmployeeAssessedPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any EmployeeAssessed');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EmployeeAssessed $employeeassessed): bool
    {
        return $user->checkPermissionTo('view EmployeeAssessed');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create EmployeeAssessed');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EmployeeAssessed $employeeassessed): bool
    {
        return $user->checkPermissionTo('update EmployeeAssessed');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EmployeeAssessed $employeeassessed): bool
    {
        return $user->checkPermissionTo('delete EmployeeAssessed');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EmployeeAssessed $employeeassessed): bool
    {
        return $user->checkPermissionTo('restore EmployeeAssessed');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EmployeeAssessed $employeeassessed): bool
    {
        return $user->checkPermissionTo('force-delete EmployeeAssessed');
    }
}
