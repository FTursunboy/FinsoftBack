<?php

namespace App\DTO;

use App\Http\Requests\Api\MovementDocument\MovementDocumentRequest;
use Illuminate\Http\Request;

class MovementDocumentDTO
{
    public function  __construct(public string $date, public int $organization_id,
                     public int $sender_storage_id,  public int $recipient_storage_id, public string $comment, public array $goods)
    {
    }

    public static function fromRequest(MovementDocumentRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('sender_storage_id'),
            $request->get('recipient_storage_id'),
            $request->get('comment'),
            $request->get('goods')

        );
    }
}
