<?php

namespace App\Repositories\Contracts\Document;

use App\DTO\Document\DeleteDocumentGoodsDTO;
use App\DTO\Document\MovementDocumentDTO;
use App\Models\Document;
use App\Models\MovementDocument;
use Illuminate\Pagination\LengthAwarePaginator;

interface MovementDocumentRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(MovementDocumentDTO $DTO) :MovementDocument;

    public function update(MovementDocument $document, MovementDocumentDTO $DTO) :MovementDocument;

    public function changeHistory(Document $document);

    public function documentAuthor();

    public function deleteDocumentGoods(DeleteDocumentGoodsDTO $DTO);

    public function approve(array $data);

    public function unApprove(array $data);

    public function massDelete(array $data);
}
