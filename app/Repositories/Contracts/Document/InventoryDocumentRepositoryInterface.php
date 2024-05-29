<?php

namespace App\Repositories\Contracts\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\InventoryDocumentDTO;
use App\DTO\Document\InventoryDocumentUpdateDTO;
use App\Models\InventoryDocument;
use Illuminate\Pagination\LengthAwarePaginator;

interface InventoryDocumentRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(InventoryDocumentDTO $DTO) :InventoryDocument;

    public function update(InventoryDocument $document, InventoryDocumentUpdateDTO $DTO) :InventoryDocument;

    public function deleteDocumentGoods(DeleteDocumentGoodsDTO $DTO);

}
