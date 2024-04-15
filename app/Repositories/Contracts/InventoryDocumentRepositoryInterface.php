<?php

namespace App\Repositories\Contracts;

use App\DTO\DocumentDTO;
use App\DTO\DocumentUpdateDTO;
use App\DTO\InventoryDocumentDTO;
use App\DTO\MovementDocumentDTO;
use App\DTO\OrderDocumentDTO;
use App\Models\Document;
use App\Models\InventoryDocument;
use App\Models\MovementDocument;
use App\Models\OrderDocument;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PhpParser\Comment\Doc;

interface InventoryDocumentRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(InventoryDocumentDTO $DTO) :InventoryDocument;

    public function update(InventoryDocument $document, InventoryDocumentDTO $DTO) :InventoryDocument;

}
