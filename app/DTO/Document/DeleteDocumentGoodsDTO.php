<?php

namespace App\DTO\Document;

use App\Http\Requests\Api\Document\DeleteDocumentGoodRequest;

class DeleteDocumentGoodsDTO
{
    public function  __construct(public array $ids)
    {
    }

    public static function fromRequest(DeleteDocumentGoodRequest $request) :self
    {
        return new static(
            $request->get('ids'),
        );
    }
}
