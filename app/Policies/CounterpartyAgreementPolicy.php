<?php

namespace App\Policies;

use App\Models\CounterpartyAgreement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CounterpartyAgreementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('counterpartyAgreement.read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CounterpartyAgreement $counterpartyAgreement): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('counterpartyAgreement.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CounterpartyAgreement $counterpartyAgreement): bool
    {
        return $user->can('counterpartyAgreement.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CounterpartyAgreement $counterpartyAgreement): bool
    {
        return $user->can('counterpartyAgreement.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CounterpartyAgreement $counterpartyAgreement): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CounterpartyAgreement $counterpartyAgreement): bool
    {
        //
    }
}
