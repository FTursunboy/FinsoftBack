<?php

namespace App\Policies;

use App\Models\MovementDocument;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MovementDocumentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, MovementDocument $movementDocument): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, MovementDocument $movementDocument): bool
    {
        return true;
    }

    public function delete(User $user, MovementDocument $movementDocument): bool
    {
        return true;
    }

    public function restore(User $user, MovementDocument $movementDocument): bool
    {
    }

    public function forceDelete(User $user, MovementDocument $movementDocument): bool
    {
    }
}
