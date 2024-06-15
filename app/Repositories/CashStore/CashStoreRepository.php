<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\ClientPaymentDTO;
use App\Enums\CashOperationType;
use App\Enums\DocumentTypes;
use App\Enums\MovementTypes;
use App\Events\DocumentApprovedEvent;
use App\Models\CashStore;
use App\Models\Document;
use App\Repositories\Contracts\CashStore\CashStoreRepositoryInterface;
use App\Repositories\Contracts\CashStore\ClientPaymentRepositoryInterface;
use App\Traits\DocNumberTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class CashStoreRepository implements CashStoreRepositoryInterface
{

    public $model = CashStore::class;

    public function index(array $data, string $type)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('cash_stores.type', $type);

        $query = $query->filter($filteredParams);

        return $query->with([
            'organization', 'balanceArticle', 'month', 'cashRegister', 'counterparty', 'author', 'currency', 'senderCashRegister', 'organizationBill', 'employee', 'responsiblePerson', 'operationType'
        ])->paginate($filteredParams['itemsPerPage']);
    }


    public function approve(array $ids)
    {
        try {
            foreach ($ids['ids'] as $id) {
                $cashStore = CashStore::find($id);

                $cashStore->update(
                    ['active' => true]
                );


            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

    }

    public function unApprove(array $data)
    {
        foreach ($data['ids'] as $id) {
            $document = CashStore::find($id);

            //todo delete previous value

            $document->update(
                ['active' => false]
            );
        }
    }
}
