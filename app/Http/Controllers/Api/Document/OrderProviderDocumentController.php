<?php

namespace App\Http\Controllers\Api\Document;

use App\DTO\Document\DocumentDTO;
use App\DTO\Document\OrderDocumentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\Document\FilterRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\OrderDocument\OrderDocumentRequest;
use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Document\OrderDocumentResource;
use App\Models\Document;
use App\Models\OrderDocument;
use App\Models\OrderType;
use App\Models\Status;
use App\Repositories\Contracts\Document\DocumentRepositoryInterface;
use App\Repositories\Contracts\Document\OrderProviderDocumentRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class OrderProviderDocumentController extends Controller
{
    use ApiResponse;

    public function __construct(public OrderProviderDocumentRepositoryInterface $repository) { }

    public function index(FilterRequest $request)
    {
        return $this->paginate(OrderDocumentResource::collection($this->repository->index($request->validated(), OrderType::PROVIDER)));
    }

    public function store(OrderDocumentRequest $request)
    {
        return $this->created(OrderDocumentResource::make($this->repository->store(OrderDocumentDTO::fromRequest($request), OrderType::PROVIDER)));
    }

    public function show(OrderDocument $orderDocument)
    {
        return $this->success(
            OrderDocumentResource::make(
                $orderDocument->load('counterparty', 'organization', 'author', 'counterpartyAgreement', 'currency', 'orderDocumentGoods')
            )
        );
    }

}
