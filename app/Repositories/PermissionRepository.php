<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\User;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Contracts\Permission;
use function PHPUnit\Framework\isFalse;

class PermissionRepository implements PermissionRepositoryInterface
{
    use Sort, FilterTrait;


    public function givePermission(User $user, array $permissions)
    {
        $permissionList = [];

        foreach ($permissions['resource'] as $item)
        {
            $permissionList[] = $item['title'];
            $permissionList = array_merge($permissionList, array_map(function($access) use($item) {
                return $item['title'] . '.' . $access;
            }, $item['access']));
        }



        $user->syncPermissions($permissionList);
    }
}
