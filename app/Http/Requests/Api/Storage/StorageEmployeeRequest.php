<?php

namespace App\Http\Requests\Api\Storage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorageEmployeeRequest extends FormRequest
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
            'employee_id' => ['required', Rule::exists('employees', 'id')],
            'from' => ['required', 'date'],
            'to' => ['required', 'date']
        ];
    }


    public function messages()
    {
        return [
            'employee_id.required' => 'Поле сотрудник обязательно для заполнения.',
            'employee_id.exists' => 'Выбранное значение для сотрудник не существует.',
            'from.required' => 'Поле дата начала обязательно для заполнения.',
            'to.required' => 'Поле дата конец обязательно для заполнения.',
        ];
    }

}
