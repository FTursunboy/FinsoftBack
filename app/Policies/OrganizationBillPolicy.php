<?php

namespace App\Policies;

use App\Models\OrganizationBill;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganizationBillPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('organizationBill.read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrganizationBill $organizationBill): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('organizationBill.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrganizationBill $organizationBill): bool
    {
        return $user->can('organizationBill.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrganizationBill $organizationBill): bool
    {
        return $user->can('organizationBill.deleted');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OrganizationBill $organizationBill): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OrganizationBill $organizationBill): bool
    {
        //
    }
}
