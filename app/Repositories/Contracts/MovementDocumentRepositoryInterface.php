<?php

namespace App\Repositories\Contracts;

use App\DTO\DocumentDTO;
use App\DTO\DocumentUpdateDTO;
use App\DTO\MovementDocumentDTO;
use App\DTO\OrderDocumentDTO;
use App\Models\Document;
use App\Models\MovementDocument;
use App\Models\OrderDocument;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PhpParser\Comment\Doc;

interface MovementDocumentRepositoryInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(MovementDocumentDTO $DTO) :MovementDocument;

    public function update(MovementDocument $document, MovementDocumentDTO $DTO) :MovementDocument;

    public function changeHistory(Document $document);

    public function approve(Document $document);

    public function unApprove(Document $document);

    public function documentAuthor();
}
