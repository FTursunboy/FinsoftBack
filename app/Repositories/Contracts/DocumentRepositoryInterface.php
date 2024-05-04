<?php

namespace App\Repositories\Contracts;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\DocumentDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\DTO\Document\OrderDocumentUpdateDTO;
use App\Models\Document;
use App\Models\OrderDocument;
use Illuminate\Pagination\LengthAwarePaginator;

interface DocumentRepositoryInterface
{
    public function index(int $status, array $data) :LengthAwarePaginator;

    public function store(DocumentDTO $DTO, int $status) :Document;

    public function update(Document $document, DocumentUpdateDTO $DTO);

    public function updateOrder(OrderDocument $document, OrderDocumentUpdateDTO $DTO) :OrderDocument;

    public function changeHistory(Document $document);

    public function approve(Document $document);

    public function unApprove(Document $document);

    public function deleteDocumentGoods(Document $document, DeleteDocumentGoodsDTO $DTO);
}
