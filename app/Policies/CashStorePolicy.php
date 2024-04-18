<?php

namespace App\Policies;

use App\Models\CashStore;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CashStorePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CashStore $cashStore): bool
    {
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, CashStore $cashStore): bool
    {
    }

    public function delete(User $user, CashStore $cashStore): bool
    {
    }

    public function restore(User $user, CashStore $cashStore): bool
    {
    }

    public function forceDelete(User $user, CashStore $cashStore): bool
    {
    }
}
