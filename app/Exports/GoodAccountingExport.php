<?php

namespace App\Exports;


use App\Models\GoodAccounting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class GoodAccountingExport implements FromCollection
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


}
