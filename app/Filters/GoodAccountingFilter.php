<?php

namespace App\Filters;

use App\Models\CashStore;
use App\Models\GoodAccounting;
use App\Traits\Sort;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Facades\DB;
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

    public function organization($value): GoodAccountingFilter
    {
        return $this->where('organization_id', $value);
    }

    public function counterpartyAgreement($value): GoodAccountingFilter
    {
        return $this->related('document', function ($query) use ($value) {
            return $query->where('counterparty_agreement_id', $value);
        });
    }

    public function good($value)
    {
        return $this->where('good_id', $value);
    }

    public function group($value)
    {
        return $this->related('good', function ($query) use ($value) {
            return $query->where('good_group_id', $value);
        });
    }

    public function startDate($value): GoodAccountingFilter
    {
        $date = Carbon::parse($value);

        $this->addSelect(DB::raw('(
            SUM(CASE WHEN good_accountings.movement_type = "приход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END) -
            SUM(CASE WHEN good_accountings.movement_type = "расход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END)
        ) as start_remainder'));
        $this->addBinding([$date, $date], 'select');

        return $this;
    }

    public function endDate($value): GoodAccountingFilter
    {

        $date = Carbon::parse($value);
        $this->addSelect(DB::raw('(
            SUM(CASE WHEN good_accountings.movement_type = "приход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END) -
            SUM(CASE WHEN good_accountings.movement_type = "расход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END)
        ) as end_remainder'));
        $this->addBinding([$date, $date], 'select');

        return $this;
    }

    public function date($value): GoodAccountingFilter
    {
        $date = Carbon::parse($value);
        $this->addSelect(DB::raw('(
            SUM(CASE WHEN good_accountings.movement_type = "приход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END) -
            SUM(CASE WHEN good_accountings.movement_type = "расход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END)
        ) as end_remainder'));
        $this->addBinding([$date, $date], 'select');
        $this->addSelect(DB::raw('(
            SUM(CASE WHEN good_accountings.movement_type = "приход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END) -
            SUM(CASE WHEN good_accountings.movement_type = "расход" AND good_accountings.date <= ? THEN good_accountings.amount ELSE 0 END)
        ) as start_remainder'));
        $this->addBinding([$date, $date], 'select');
    }


}
