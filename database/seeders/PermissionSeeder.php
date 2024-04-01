<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::query()->insert([
            ['name' => 'unit'],
            ['name' => 'unit.create'],
            ['name' => 'unit.update'],
            ['name' => 'unit.delete'],
        ]);
    }
}
