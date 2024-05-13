<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\Models\Balance;
use App\Models\Barcode;
use App\Models\CounterpartySettlement;
use App\Models\Good;
use App\Models\GoodAccounting;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\Document\Documentable;
use App\Repositories\Contracts\ReportDocumentRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\isFalse;

class ReportDocumentRepository implements ReportDocumentRepositoryInterface
{
    use Sort;

    public function getBalances(Documentable $document, array $data): LengthAwarePaginator
    {
        $filterParams = Balance::filterData($data);

        $query = Balance::where('model_id', $document->id);

        $query = $this->sort($filterParams, $query, ['creditArticle', 'debitArticle', 'organization']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function getCounterpartySettlements(Documentable $document, array $data): LengthAwarePaginator
    {
        $filterParams = CounterpartySettlement::filterData($data);

        $query = CounterpartySettlement::where('model_id', $document->id);

        $query = $this->sort($filterParams, $query, ['counterparty', 'counterpartyAgreement', 'organization']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function getGoodAccountings(Documentable $document, array $data): LengthAwarePaginator
    {
        $filterParams = GoodAccounting::filterData($data);

        $query = GoodAccounting::where('model_id', $document->id);

        $query = $this->sort($filterParams, $query, ['storage', 'good', 'organization']);

        return $query->paginate($filterParams['itemsPerPage']);
    }
}