<?php

namespace App\Policies;

use App\Models\EmployeeMovement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeMovementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, EmployeeMovement $employeeMovement): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, EmployeeMovement $employeeMovement): bool
    {
        return true;
    }

    public function delete(User $user, EmployeeMovement $employeeMovement): bool
    {
        return true;
    }

    public function restore(User $user, EmployeeMovement $employeeMovement): bool
    {
        return true;
    }

    public function forceDelete(User $user, EmployeeMovement $employeeMovement): bool
    {
    }
}
