<?php

namespace Database\Seeders;

use App\Models\Month;
use Illuminate\Database\Seeder;

class MonthSeeder extends Seeder
{
    public function run(): void
    {
        Month::query()->insert([
            ['name' => 'Январь', 'name_en' => 'January']
        ]);
        Month::query()->insert([
            ['name' => 'Февраль', 'name_en' => 'February']
        ]);
        Month::query()->insert([
            ['name' => 'Март', 'name_en' => 'March']
        ]);
        Month::query()->insert([
            ['name' => 'Апрел', 'name_en' => 'April']
        ]);
        Month::query()->insert([
            ['name' => 'Май', 'name_en' => 'May']
        ]);
        Month::query()->insert([
            ['name' => 'Июнь', 'name_en' => 'June']
        ]);
        Month::query()->insert([
            ['name' => 'Июль', 'name_en' => 'July']
        ]);
        Month::query()->insert([
            ['name' => 'Август', 'name_en' => 'August']
        ]);
        Month::query()->insert([
            ['name' => 'Сентябрь', 'name_en' => 'September']
        ]);
        Month::query()->insert([
            ['name' => 'Октябрь', 'name_en' => 'October']
        ]);
        Month::query()->insert([
            ['name' => 'Ноябрь', 'name_en' => 'November']
        ]);
        Month::query()->insert([
            ['name' => 'Декабрь', 'name_en' => 'December']
        ]);
    }
}
