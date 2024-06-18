<?php

namespace App\DTO\Document;

use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\EquipmentDocument\EquipmentDocumentRequest;

class EquipmentDocumentDTO
{
    public function  __construct(public string $date, public int $organization_id, public int $storage_id, public int $good_id,
                     public ?string $comment,  public ?int $sum,public int $amount, public ?array $goods)
    { }

    public static function fromRequest(EquipmentDocumentRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('storage_id'),
            $request->get('good_id'),
            $request->get('comment'),
            $request->get('sum'),
            $request->get('amount'),
            $request->get('goods'),
        );
    }
}
