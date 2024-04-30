<?php

namespace App\Http\Requests\Api\Schedule;

use Carbon\WeekDay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'data' => ['array', 'required'],
            'data.*.month_id' => ['required', 'exists:months,id'],
            'data.*.number_of_hours' => ['required'],
            'weeks' => ['required', 'array'],
            'weeks.*.week' => [
                'required',
                'integer',
//                Rule::enum(WeekDay::class)
            ],
            'weeks.*.hour' => ['required', 'integer', 'between:0,24']
        ];
    }
}
