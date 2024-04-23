<?php

namespace Database\Seeders;

use App\Models\OperationType;
use Illuminate\Database\Seeder;

class OperationTypeSeeder extends Seeder
{
    public function run(): void
    {
        OperationType::insert([
            [
                'title' => 'Оплата от клиента'
            ],
            [
                'title' => 'Снятие с Р/С'
            ],
            [
                'title' => 'Получение с другой кассы'
            ],
            [
                'title' => 'Вложение'
            ],
            [
                'title' => 'Получение кредита'
            ],
            [
                'title' => 'Возврат от поставщика'
            ],
            [
                'title' => 'Возврат от подотчетника'
            ],
            [
                'title' => 'Прочие доходы'
            ],
            [
                'title' => 'Прочие приходы'
            ]
        ]);
    }
}
