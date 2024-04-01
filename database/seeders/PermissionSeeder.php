<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::query()->insert([
            ['name' => 'unit',
                'guard_name' => 'web',],
            ['name' => 'unit.create',
                'guard_name' => 'web',],
            ['name' => 'unit.update',
                'guard_name' => 'web',],
            ['name' => 'unit.delete',
                'guard_name' => 'web',],
        ]);
    }
}
