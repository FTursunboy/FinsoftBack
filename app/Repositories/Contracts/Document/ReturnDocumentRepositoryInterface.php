<?php

namespace App\Repositories\Contracts\Document;

use App\DTO\Document\DocumentDTO;
use App\DTO\Document\DocumentUpdateDTO;
use App\Models\Document;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReturnDocumentRepositoryInterface
{
    public function index(int $status, array $data) :LengthAwarePaginator;

    public function store(DocumentDTO $DTO) :Document;

    public function update(Document $document, DocumentUpdateDTO $DTO);

    public function changeHistory(Document $document);

    public function approve(Document $document);

    public function unApprove(Document $document);
}
