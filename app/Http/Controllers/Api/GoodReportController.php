<?php

namespace App\Http\Controllers\Api;

use App\Exports\GoodAccountingExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Report\FilterRequest;
use App\Http\Resources\GoodAccountingResource;
use App\Http\Resources\GoodReportResource;
use App\Repositories\Contracts\GoodReportRepositoryInterface;
use App\Traits\ApiResponse;
use Maatwebsite\Excel\Facades\Excel;


class GoodReportController extends Controller
{
    use ApiResponse;

    public function __construct(public GoodReportRepositoryInterface $repository)
    {
    }

    public function index(FilterRequest $request)
    {
        return $this->paginate(GoodReportResource::collection($this->repository->index($request->validated())));
    }

    public function export(FilterRequest $request)
    {
       return Excel::download(new GoodAccountingExport($this->repository->export($request->validated())), 'отчет.xls');
    }
}
