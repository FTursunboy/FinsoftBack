<?php

namespace App\Repositories\Contracts\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\DocumentDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\DTO\Document\OrderDocumentUpdateDTO;
use App\DTO\Document\ServiceDTO;
use App\Models\Document;
use App\Models\OrderDocument;
use App\Models\Service;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServiceRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(ServiceDTO $DTO);

    public function update(Service $document, ServiceDTO $DTO);

    public function changeHistory(Service $document);

    public function approve(array $data);

    public function unApprove(array $data);

    public function deleteDocumentGoods(DeleteDocumentGoodsDTO $DTO);

    public function massDelete(array $ids);
}
