<?php

namespace App\Rules;

use App\Models\ExchangeRate;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class ExchangeRateRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        $date = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');

        $currency = app('request')->route('currency');

        if (!$currency) {
            return false;
        }

        $latestRate = ExchangeRate::where('currency_id', $currency->id)
            ->where('date', '=', $date)
            ->first();

        return !$latestRate;

    }

    public function message(): string
    {
        return 'На этот день уже был записан курс валюты!';
    }
}
