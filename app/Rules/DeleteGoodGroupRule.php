<?php

namespace App\Rules;

use App\Models\ExchangeRate;
use App\Models\GoodGroup;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class DeleteGoodGroupRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        foreach ($value as $item){
            $group = GoodGroup::where('id', $item)->first();
            if ($group->goods->where('deleted_at', null)->isNotEmpty()) {
                return false;
            }
        }
        return true;

    }

    public function message(): string
    {
        return 'В этой группе есть данные!';
    }
}
