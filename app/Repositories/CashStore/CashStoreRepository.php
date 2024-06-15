<?php

namespace App\Repositories\CashStore;

use App\DTO\CashStore\ClientPaymentDTO;
use App\Enums\CashOperationType;
use App\Enums\DocumentTypes;
use App\Enums\MovementTypes;
use App\Events\CashStore\AccountablePersonEvent;
use App\Events\CashStore\AnotherCashRegisterEvent;
use App\Events\CashStore\BalanceEvent;
use App\Events\CashStore\CashEvent;
use App\Events\CashStore\CounterpartySettlementEvent;
use App\Events\CashStore\CreditEvent;
use App\Events\CashStore\IncomeEvent;
use App\Events\CashStore\InvestmentEvent;
use App\Events\CashStore\OrganizationBillEvent;
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

                if ($cashStore->active) {
                    $cashStore->update(
                        ['active' => false]
                    );
                }

                $cashStore->update(
                    ['active' => true]
                );

                if ($cashStore->operationType_id == 1) {
                    CashEvent::dispatch($cashStore, MovementTypes::Income);
                    CounterpartySettlementEvent::dispatch($cashStore, MovementTypes::Outcome);
                } elseif ($cashStore->operationType_id == 2) {
                    CashEvent::dispatch($cashStore, MovementTypes::Income);
                    OrganizationBillEvent::dispatch($cashStore, MovementTypes::Outcome);
                } elseif ($cashStore->operationType_id == 3) {
                    AnotherCashRegisterEvent::dispatch($cashStore, MovementTypes::Income, $cashStore->cashRegister_id);
                    AnotherCashRegisterEvent::dispatch($cashStore, MovementTypes::Outcome, $cashStore->senderCashRegister_id);
                } elseif ($cashStore->operationType_id == 4) {
                    CashEvent::dispatch($cashStore, MovementTypes::Income);
                    InvestmentEvent::dispatch($cashStore, MovementTypes::Income);
                } elseif ($cashStore->operationType_id == 5) {
                    CashEvent::dispatch($cashStore, MovementTypes::Income);
                    CreditEvent::dispatch($cashStore, MovementTypes::Income);
                } elseif ($cashStore->operationType_id == 6) {
                    CashEvent::dispatch($cashStore, MovementTypes::Income);
                    CounterpartySettlementEvent::dispatch($cashStore, MovementTypes::Outcome);
                } elseif ($cashStore->operationType_id == 7) {
                    CashEvent::dispatch($cashStore, MovementTypes::Income);
                    AccountablePersonEvent::dispatch($cashStore, MovementTypes::Outcome);
                } elseif ($cashStore->operationType_id == 8) {
                    CashEvent::dispatch($cashStore, MovementTypes::Income);
                    IncomeEvent::dispatch($cashStore, MovementTypes::Outcome);
                } elseif ($cashStore->operationType_id == 9) {
                    CashEvent::dispatch($cashStore, MovementTypes::Income);
                    BalanceEvent::dispatch($cashStore, MovementTypes::Income);
                }

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

    public function massDelete(array $ids)
    {
        foreach ($ids['ids'] as $id) {
            $document = CashStore::where('id', $id)->first();


            if ($document->active) {
                $document->update([
                    'active' => false
                ]);

                //todo  delete all document data
            }

            $document->delete();
        }
    }
}
