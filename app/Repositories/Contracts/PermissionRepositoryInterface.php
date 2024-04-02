<?php

namespace App\Repositories\Contracts;

use App\DTO\BarcodeDTO;
use App\Enums\ResourceTypes;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface PermissionRepositoryInterface
{
    public function givePermission(User $user, array $permissions);

    public function getPermissions(User $user, ResourceTypes $resourceTypes) : array;
}
