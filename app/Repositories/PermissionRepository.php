<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\Enums\Operations;
use App\Enums\ResourceTypes;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\Resource;
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
        $this->revokePermissions($user, ResourceTypes::AdminPanel);

        $permissionList = [];

        foreach ($permissions['resource'] as $item)
        {
            $permissionList[] = $item['title'];
            $permissionList = array_merge($permissionList, array_map(function($access) use($item) {
                return $item['title'] . '.' . $access;
            }, $item['access']));
        }

        $user->givePermissionTo($permissionList);
    }


    public function getPermissions(User $user, ResourceTypes $resourceTypes) : array
    {

        $userPermissions = $user->permissionList();

        $resources = Resource::query()->where('type', $resourceTypes)->get();

        $resourcePermissions = [];

        foreach ($resources as $resource) {

            $accessList = [];

            foreach ($userPermissions as $permission) {
                if (str_starts_with($permission, $resource->name . '.')) {
                    $accessList[] = substr($permission, strlen($resource->name . '.'));
                }
            }

            $resourcePermissions[] = [
                'title' => $resource->name,
                'ru_title' => $resource->ru_name,
                'access' => $accessList,
            ];
        }

        return $resourcePermissions;
    }

    public function givePodsystemPermission(User $user, array $permissions)
    {
        $this->revokePermissions($user, ResourceTypes::PodSystem );
        $user->givePermissionTo($permissions['permissions']);
    }

    private function revokePermissions(User $user, ResourceTypes $type)
    {
        $permissionsToRevoke = [];
        $resources = Resource::where('type', $type)->get()->pluck('name');

        foreach ($resources as $permission) {
            $permissionsToRevoke[] = $permission;
            foreach (Operations::cases() as $operation) {
                $permissionsToRevoke[] = $permission . '.' . $operation->value ;
            }
        }

        $user->revokePermissionTo($permissionsToRevoke);
    }




}
