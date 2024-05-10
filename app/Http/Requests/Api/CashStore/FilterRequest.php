<?php

namespace App\Http\Requests\Api\CashStore;

use App\Models\CashStore;
use Illuminate\Foundation\Http\FormRequest;


class FilterRequest extends FormRequest
{
    public function rules(): array
    {
        $model = $this->getModel();

        $fillableFields = $this->getFillable($model);

        return [
            'search' => 'string|nullable|max:50',
            'itemsPerPage' => 'integer|nullable',
            'orderBy' => 'nullable|in:id,name,sender.name,organization.name,counterparty.name,counterpartyAgreement.name,author.name,
             organizationBill.name,senderCashRegister.name,employee.name,cashStore.name,storage.name,currency.name' . implode(',', $fillableFields),
            'sort' => 'in:asc,desc',
            'filterData' => 'nullable|array',
            'operation_type_id' => 'nullable|integer'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    private function getFillable($model): array
    {
        return $model->getFillable();
    }

    private function getModel()
    {
        return new CashStore();
    }
}
