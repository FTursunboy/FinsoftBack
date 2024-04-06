<?php

namespace App\Policies;

use App\Models\PriceType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PriceTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('priceType.read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PriceType $priceType): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('priceType.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PriceType $priceType): bool
    {
        return $user->can('priceType.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PriceType $priceType): bool
    {
        return $user->can('priceType.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PriceType $priceType): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PriceType $priceType): bool
    {
        //
    }
}
