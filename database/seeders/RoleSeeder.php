<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::insert([
            ['name' => 'Клиент'],
            ['name' => 'Поставщик'],
            ['name' => 'Прочие'],
        ]);

        Unit::create([
            'name' => 'кг'
        ]);
        Unit::create([
            'name' => 'м'
        ]);
    }
}
