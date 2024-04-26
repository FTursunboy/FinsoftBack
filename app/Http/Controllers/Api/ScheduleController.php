<?php

namespace App\Http\Controllers\Api;

use App\DTO\BarcodeDTO;
use App\DTO\ScheduleDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BarcodeRequest;
use App\Http\Requests\Api\IndexRequest;
use App\Http\Requests\Api\Schedule\ScheduleRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\BarcodeResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\MonthResource;
use App\Http\Resources\WorkerScheduleResource;
use App\Models\Barcode;
use App\Models\Good;
use App\Models\Month;
use App\Repositories\BarcodeRepository;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    use ApiResponse;

    public function __construct(public ScheduleRepositoryInterface $repository) { }

    public function index(IndexRequest $request)
    {
        return $this->paginate(WorkerScheduleResource::collection($this->repository->index($request->validated())));
    }

    public function store(ScheduleRequest $request)
    {
        return $this->created(WorkerScheduleResource::make($this->repository->store(ScheduleDTO::fromRequest($request))));
    }

    public function months(IndexRequest $request)
    {
        return $this->paginate(MonthResource::collection($this->repository->month($request->validated())));
    }

}
