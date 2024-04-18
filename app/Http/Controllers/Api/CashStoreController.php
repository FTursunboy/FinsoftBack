<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashStoreRequest;
use App\Http\Resources\CashStoreResource;
use App\Models\CashStore;

class CashStoreController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', CashStore::class);

        return CashStoreResource::collection(CashStore::all());
    }

    public function store(CashStoreRequest $request)
    {
        $this->authorize('create', CashStore::class);

        return new CashStoreResource(CashStore::create($request->validated()));
    }

    public function show(CashStore $cashStore)
    {
        $this->authorize('view', $cashStore);

        return new CashStoreResource($cashStore);
    }

    public function update(CashStoreRequest $request, CashStore $cashStore)
    {
        $this->authorize('update', $cashStore);

        $cashStore->update($request->validated());

        return new CashStoreResource($cashStore);
    }

    public function destroy(CashStore $cashStore)
    {
        $this->authorize('delete', $cashStore);

        $cashStore->delete();

        return response()->json();
    }
}
