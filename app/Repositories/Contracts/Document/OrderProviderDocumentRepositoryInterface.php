<?php

namespace App\Repositories\Contracts\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\DocumentDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\DTO\Document\OrderDocumentDTO;
use App\DTO\Document\OrderDocumentUpdateDTO;
use App\Models\Document;
use App\Models\OrderDocument;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderProviderDocumentRepositoryInterface
{
    public function index(array $data, int $type) :LengthAwarePaginator;

    public function store(OrderDocumentDTO $DTO, int $type);

    public function updateOrder(OrderDocument $document, OrderDocumentUpdateDTO $DTO): OrderDocument;

    public function approve(array $data);
}
