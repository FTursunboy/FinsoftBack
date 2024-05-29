<?php

namespace App\Repositories\Contracts\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\DocumentDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\DTO\Document\OrderDocumentUpdateDTO;
use App\Models\Document;
use App\Models\OrderDocument;
use Illuminate\Pagination\LengthAwarePaginator;

interface ClientDocumentRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(DocumentDTO $DTO) :Document;

    public function update(Document $document, DocumentUpdateDTO $DTO);

    public function changeHistory(Document $document);

    public function approve(array $data);

    public function unApprove(array $data);

    public function deleteDocumentGoods(DeleteDocumentGoodsDTO $DTO);

    public function massDelete(array $ids);
}
