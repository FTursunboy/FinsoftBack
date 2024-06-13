<?php

namespace App\Repositories\Contracts\Document;

use App\DTO\Document\OrderDocumentDTO;
use App\DTO\Document\OrderDocumentUpdateDTO;
use App\Models\OrderDocument;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderClientDocumentRepositoryInterface
{
    public function index(array $data, int $type) :LengthAwarePaginator;

    public function store(OrderDocumentDTO $DTO, int $type);

    public function updateOrder(OrderDocument $document, OrderDocumentUpdateDTO $DTO): OrderDocument;

    public function approve(array $data);

    public function unApprove(array $data);

    public function massDelete(array $data);


}
