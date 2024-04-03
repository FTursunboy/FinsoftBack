<?php

namespace Database\Seeders;

use App\Enums\Operations;
use App\Enums\ResourceTypes;
use App\Models\Resource;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $resources = Resource::query()->get();
        foreach ($resources as $resource) {
            Permission::updateOrInsert(
                ['name' => $resource->name],
                ['guard_name' => 'web']
            );
        }


        $resources = Resource::query()->where('type', '!=', ResourceTypes::Report)->get();

        foreach ($resources as $permission) {
            foreach (Operations::cases() as $operation) {
                Permission::updateOrInsert(
                    ['name' => $permission->name . '.' . $operation->value],
                    ['guard_name' => 'web']
                );
            }
        }
    }
}
