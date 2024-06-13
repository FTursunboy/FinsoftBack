<?php

namespace App\DTO\Document;

use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\InventoryOperationRequest;

class InventoryOperationDTO
{
    public function  __construct(public string $date, public int $organization_id,
                     public int $storage_id, public ?array $goods, public ?string $comment, public int $currency_id, public ?int $sum, public string $status)
    {
    }

    public static function fromRequest(InventoryOperationRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('storage_id'),
            $request->get('goods'),
            $request->get('comment'),
            $request->get('currency_id'),
            $request->get('sum'),
            $request->get('status')
        );
    }
}
