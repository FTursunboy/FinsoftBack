<?php

namespace App\Repositories\Contracts;

use App\DTO\DocumentDTO;
use App\DTO\DocumentUpdateDTO;
use App\Models\Document;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PhpParser\Comment\Doc;

interface DocumentRepositoryInterface
{
    public function index(int $status, array $data) :LengthAwarePaginator;

    public function store(DocumentDTO $DTO, int $status) :Document;

    public function update(Document $document, DocumentUpdateDTO $DTO) :Document;

    public function changeHistory(Document $document);

    public function approve(Document $document);

    public function unApprove(Document $document);
}
