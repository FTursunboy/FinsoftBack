<?php

namespace App\Policies;

use App\Models\ReportCard;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportCardPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, ReportCard $reportCard): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, ReportCard $reportCard): bool
    {
    }

    public function delete(User $user, ReportCard $reportCard): bool
    {
    }

    public function restore(User $user, ReportCard $reportCard): bool
    {
    }

    public function forceDelete(User $user, ReportCard $reportCard): bool
    {
    }
}
