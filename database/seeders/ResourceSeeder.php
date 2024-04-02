<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        Resource::query()->insert([
            ['name' => 'admin_panel', 'parent_id' => null,],
            ['name' => 'unit', 'parent_id' => 1,],
            ['name' => 'organizationBill', 'parent_id' => 1,],
            ['name' => 'nomenclature', 'parent_id' => 1,],
            ['name' => 'user', 'parent_id' => 1,],
            ['name' => 'counterparty', 'parent_id' => 1,],
            ['name' => 'organization', 'parent_id' => 1,],
            ['name' => 'employee', 'parent_id' => 1,],
            ['name' => 'position', 'parent_id' => 1,],
            ['name' => 'priceType', 'parent_id' => 1,],
            ['name' => 'storage', 'parent_id' => 1,],
            ['name' => 'cashRegister', 'parent_id' => 1,],
            ['name' => 'programSettings', 'parent_id' => 1,],
        ]);
    }
}
