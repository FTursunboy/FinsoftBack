<?php

namespace Database\Seeders;

use App\Enums\ResourceTypes;
use App\Models\Resource;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        Resource::query()->insert([
            ['name' => 'admin_panel', 'parent_id' => null, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Админ панель'],
            ['name' => 'unit', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Единицы измерения'],
            ['name' => 'organizationBill', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Банковские Счета организации'],
            ['name' => 'nomenclature', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Номенклатура'],
            ['name' => 'user', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Пользователи'],
            ['name' => 'counterparty', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Контрагенты'],
            ['name' => 'organization', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Организации'],
            ['name' => 'employee', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Сотрудники'],
            ['name' => 'position', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Должность'],
            ['name' => 'priceType', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Тип цены'],
            ['name' => 'storage', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Склад'],
            ['name' => 'cashRegister', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Касса'],
            ['name' => 'programSettings', 'parent_id' => 1, 'type' => ResourceTypes::AdminPanel->value, 'ru_name' => 'Настройки программы'],
        ]);
    }
}
