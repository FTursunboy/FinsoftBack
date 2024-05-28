<?php

namespace App\Exports;


use App\Models\GoodAccounting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GoodAccountingExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(public Collection $collection)
    {

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->collection->map(function ($item) {
            return $item->attributes;
        });
    }

    public function headings(): array
    {
        return [
            'Товар',
            'Группа',
            'Начальный остаток',
            'Приход',
            'Расход',
            'Остаток на конец',
        ];
    }

    public function map($row): array
    {
        return [
            $row['name'] ?? '',
            $row['group_name'] ?? '',
            $row['start_remainder'] ?? '',
            $row['income'] ?? '',
            $row['outcome'] ?? '',
            $row['remainder'] ?? '',
        ];
    }
}
