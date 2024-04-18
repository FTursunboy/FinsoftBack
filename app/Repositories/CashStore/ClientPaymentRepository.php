<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\DTO\ClientPaymentDTO;
use App\Models\Barcode;
use App\Models\CashStore;
use App\Models\Good;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\CashStoreRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\isFalse;

class ClientPaymentRepository implements CashStoreRepositoryInterface
{

    public $model = CashStore::class;


    public function clientPayment(ClientPaymentDTO $dto)
    {
        dd($dto);
    }
}
