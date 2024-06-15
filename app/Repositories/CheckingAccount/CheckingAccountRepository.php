<?php

namespace App\Repositories\CheckingAccount;

use App\Models\CashStore;
use App\Models\CheckingAccount;
use App\Repositories\Contracts\CheckingAccount\CheckingAccountRepositoryInterface;

class CheckingAccountRepository implements CheckingAccountRepositoryInterface
{
    public $model = CheckingAccount::class;

    public function index(array $data, string $type)
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::where('type', $type);

        $query = $query->filter($filteredParams);

        return $query->with([
            'organization', 'checkingAccount', 'counterparty', 'author', 'currency', 'senderCashRegister', 'organizationBill', 'employee', 'operationType'
        ])->paginate($filteredParams['itemsPerPage']);
    }


    public function massDelete(array $ids)
    {
        foreach ($ids['ids'] as $id) {
            $document = CheckingAccount::where('id', $id)->first();


            if($document->active) {
                $document->update([
                    'active' => false
                ]);

                //todo  delete all document data
            }

            $document->delete();
        }
    }

    public function approve(array $ids)
    {
        foreach ($ids['ids'] as $id) {
            $document = CheckingAccount::where('id', $id)->first();
            $document->update([
                'active' => true
            ]);
        }
    }

    public function unApprove(array $ids)
    {
        foreach ($ids['ids'] as $id) {
            $document = CheckingAccount::where('id', $id)->first();
            $document->update([
                'active' => false
            ]);
        }
    }
}
