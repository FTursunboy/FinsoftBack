<?php

namespace App\Policies;

use App\Models\SalaryDocument;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalaryDocumentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SalaryDocument $salaryDocument): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, SalaryDocument $salaryDocument): bool
    {
        return true;
    }

    public function delete(User $user, SalaryDocument $salaryDocument): bool
    {
        return true;
    }

    public function restore(User $user, SalaryDocument $salaryDocument): bool
    {
        return true;
    }

    public function forceDelete(User $user, SalaryDocument $salaryDocument): bool
    {
        return true;
    }
}
