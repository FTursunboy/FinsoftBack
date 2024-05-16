<?php

namespace App\Filters;

use App\Models\CashStore;
use App\Models\GoodAccounting;
use App\Traits\Sort;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Str;

class GoodAccountingFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */

    use Sort {
        sort as traitSort;
    }

    protected $model = GoodAccounting::class;

    public function start($value) :GoodAccountingFilter
    {
        $date = Carbon::parse($value);
        return $this->where('date', '>=', $date);
    }

    public function end($value) :GoodAccountingFilter
    {
        $date = Carbon::parse($value);
        return $this->where('date', '<=', $date);
    }

    public function date($value) :GoodAccountingFilter
    {
        $date = Carbon::parse($value);
        return $this->where('date', '<=', $date);
    }


}
