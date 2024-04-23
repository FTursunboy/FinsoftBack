<?php

namespace App\Policies;

use App\Models\Hiring;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HiringPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Hiring $hiring): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;

    }

    public function update(User $user, Hiring $hiring): bool
    {
        return true;
    }

    public function delete(User $user, Hiring $hiring): bool
    {
        return true;
    }

    public function restore(User $user, Hiring $hiring): bool
    {
    }

    public function forceDelete(User $user, Hiring $hiring): bool
    {
    }
}
