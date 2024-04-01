<?php

namespace App\Repositories\Contracts;

use App\Models\Currency;
use Illuminate\Pagination\LengthAwarePaginator;

interface ExchangeRateInterface
{
    public function index(Currency $currency, array $data): LengthAwarePaginator;

}
