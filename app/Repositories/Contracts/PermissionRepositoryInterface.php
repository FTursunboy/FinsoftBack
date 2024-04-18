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
    public function giveAdminPanelPermission(User $user, array $permissions);

    public function getPermissions(User $user, ResourceTypes $resourceTypes) : array;

    public function givePodSystemPermission(User $user, array $permissions);

    public function giveReportPermission(User $user, array $permissions);

    public function giveDocumentPermission(User $user, array $permissions);
}
