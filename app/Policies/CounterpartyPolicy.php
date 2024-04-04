<?php

namespace App\Policies;

use App\Models\Counterparty;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CounterpartyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('counterparty.read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Counterparty $counterparty): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('counterparty.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Counterparty $counterparty): bool
    {
        return $user->can('counterparty.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Counterparty $counterparty): bool
    {
        return $user->can('counterparty.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Counterparty $counterparty): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Counterparty $counterparty): bool
    {
        //
    }
}
