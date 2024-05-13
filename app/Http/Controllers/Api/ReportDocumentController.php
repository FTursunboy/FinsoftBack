<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BalanceResource;
use App\Models\Balance;
use App\Repositories\Contracts\Document\Documentable;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ReportDocumentController extends Controller
{
    use ApiResponse;
    public function getBalance(Documentable $document)
    {
        $balances = Balance::where('model_id', $document->id)->get();

        return $this->success(BalanceResource::collection($balances->load(['creditArticle', 'debitArticle', 'organization'])));
    }
}
