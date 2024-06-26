<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\BalanceArticle;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\Group;
use App\Models\OrderStatus;
use App\Models\Setting;
use Carbon\Carbon;
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
            OperationTypeSeeder::class,
            SettingsSeeder::class,
            ResourceSeeder::class,
            PermissionSeeder::class,
            OrderTypeSeeder::class,
            MonthSeeder::class
        ]);

        Currency::create([
            'name' => 'Сомони',
            'symbol_code' => 'TJS',
            'digital_code' => 23,
            'default' => true
        ]);

        Currency::create([
            'name' => 'Доллар',
            'symbol_code' => 'USD',
            'digital_code' => 213,
            'default' => true
        ]);

        Currency::create([
            'name' => 'Рубль',
            'symbol_code' => 'RUS',
            'digital_code' => 111,
            'default' => true
        ]);


        ExchangeRate::create([
            'date' => Carbon::now(),
            'currency_id' => 2,
            'value' => 11,
        ]);
        ExchangeRate::create([
            'date' => Carbon::now(),
            'currency_id' => 3,
            'value' => 0.12,
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
        OrderStatus::create([
            'name' => 'Принят',
        ]);
        OrderStatus::create([
            'name' => 'Отклонен',
        ]);
        OrderStatus::create([
            'name' => 'Завершен',
        ]);
        BalanceArticle::create([
            'name' => 'Статья 1',
        ]);
        BalanceArticle::create([
            'name' => 'Статья 2',
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

        \App\Models\User::factory()->create([
            'name' => 'Suhrob',
            'email' => 'gafurows@gmail.com',
            'login' => 'sero',
            'password' => Hash::make('password'),
            'group_id' => 1
        ])->assignRole('admin')
            ->syncPermissions($this->permissionList());

        $this->call([
            RoleSeeder::class,
            StatusSeeder::class
        ]);

        $this->call(FactorySeeder::class);
    }

    public function permissionList()
    {
       return Permission::get()->pluck('name');
    }
}
