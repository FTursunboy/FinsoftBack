<?php

namespace App\Policies;

use App\Models\Firing;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FiringPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Firing $firing): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Firing $firing): bool
    {
        return true;
    }

    public function delete(User $user, Firing $firing): bool
    {
        return true;
    }

    public function restore(User $user, Firing $firing): bool
    {
        return true;
    }

    public function forceDelete(User $user, Firing $firing): bool
    {
        return true;
    }
}
