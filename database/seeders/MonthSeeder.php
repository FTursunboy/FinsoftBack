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
    }
}
