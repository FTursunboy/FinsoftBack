<?php

namespace App\Repositories\Contracts\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\DocumentDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\DTO\Document\InventoryOperationDTO;
use App\DTO\Document\OrderDocumentUpdateDTO;
use App\Models\Document;
use App\Models\InventoryOperation;
use App\Models\OrderDocument;
use Illuminate\Pagination\LengthAwarePaginator;

interface InventoryOperationRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(InventoryOperationDTO $DTO) :InventoryOperation;

    public function update(InventoryOperation $document, InventoryOperationDTO $DTO);

    public function approve(array $data);

    public function unApprove(array $data);

    public function deleteDocumentGoods(DeleteDocumentGoodsDTO $DTO);

}
