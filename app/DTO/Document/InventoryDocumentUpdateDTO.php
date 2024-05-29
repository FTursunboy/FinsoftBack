<?php

namespace App\DTO\Document;

use App\Http\Requests\Api\InventoryDocument\InventoryDocumentUpdateRequest;

class InventoryDocumentUpdateDTO
{
    public function  __construct(public string $date, public int $organization_id,
                     public int $storage_id,  public int $responsible_person_id, public ?string $comment, public array $goods)
    {
    }

    public static function fromRequest(InventoryDocumentUpdateRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('storage_id'),
            $request->get('responsible_person_id'),
            $request->get('comment'),
            $request->get('goods'),
        );
    }
}
