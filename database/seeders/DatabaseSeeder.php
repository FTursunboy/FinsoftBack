<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Group;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            ResourceSeeder::class,
            PermissionSeeder::class,
        ]);

        Role::create([
            'name' => 'admin',
        ]);

        Role::create([
            'name' => 'user',
        ]);
        Group::create([
            'name' => 'group',
            'type' => 1
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'login' => 'admin',
            'password' => Hash::make('password'),
            'group_id' => 1
        ])->assignRole('admin')
            ->syncPermissions($this->permissionList());

        \App\Models\User::factory()->create([
            'name' => 'Rustam',
            'email' => 'rustamjon@gmail.com',
            'login' => 'rustamjon',
            'password' => Hash::make('password'),
            'group_id' => 1
        ])->assignRole('admin')
            ->syncPermissions($this->permissionList());

        \App\Models\User::factory()->create([
            'name' => 'Jamshed',
            'email' => 'jamshed@gmail.com',
            'login' => 'jamshed',
            'password' => Hash::make('password'),
            'group_id' => 1
        ])->assignRole('admin')
            ->syncPermissions($this->permissionList());

        \App\Models\User::factory()->create([
            'name' => 'Sheroz',
            'email' => 'sheroz@gmail.com',
            'login' => 'sheroz',
            'password' => Hash::make('password'),
            'group_id' => 1
        ])->assignRole('admin')
            ->syncPermissions($this->permissionList());

        $this->call([
            RoleSeeder::class,
            StatusSeeder::class,
            FactorySeeder::class
        ]);
    }

    public function permissionList()
    {
       return Permission::get()->pluck('name');

    }
}
