<?php

namespace App\Repositories\Contracts;

use App\DTO\Document\InventoryDocumentDTO;
use App\DTO\Document\InventoryDocumentUpdateDTO;
use App\Models\InventoryDocument;
use Illuminate\Pagination\LengthAwarePaginator;

interface InventoryDocumentRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(InventoryDocumentDTO $DTO) :InventoryDocument;

    public function update(InventoryDocument $document, InventoryDocumentUpdateDTO $DTO) :InventoryDocument;

}
