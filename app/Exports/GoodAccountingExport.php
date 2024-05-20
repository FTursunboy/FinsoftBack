<?php

namespace App\Exports;


use App\Models\GoodAccounting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GoodAccountingExport implements FromCollection, WithHeadings
{
    public function __construct(public Collection $collection)
    {

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->collection;
    }


    public function headings(): array
    {
        return [
            'Товар',         // или 'Товар ID
            'Группа',         // или 'Товар ID
            'Остаток На начало',         // или 'Товар ID
            'Приход',          // или 'Приход'
            'Расход',         // или 'Расход'
            'Остаток на конец',       // или 'Остаток'
        ];
    }
}
