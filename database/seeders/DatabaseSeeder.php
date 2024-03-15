<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'login' => 'admin',
            'password' => Hash::make('password'),
            'group_id' => 1
        ])->assignRole('admin');

        $this->call([
            RoleSeeder::class,
            StatusSeeder::class,
            FactorySeeder::class
        ]);
    }
}
