<?php

namespace App\Repositories\Contracts;

use App\DTO\Document\SalaryDocumentDTO;
use App\DTO\LoginDTO;
use App\Models\SalaryDocument;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

interface SalaryDocumentRepositoryInterface
{

    public function index(array $data) :LengthAwarePaginator;

    public function store(SalaryDocumentDTO $DTO) ;

}
