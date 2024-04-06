<?php

namespace App\DTO;

use App\Http\Requests\Api\Document\DocumentRequest;
use Illuminate\Http\Request;

class DocumentDTO
{
    public function  __construct(public string $date, public int $counterparty_id, public int $counterparty_agreement_id, public int $organization_id,
                     public int $storage_id, public ?array $goods, public ?string $comment, public ?int $saleInteger, public ?int $salePercent, public int $currency_id)
    {
    }

    public static function fromRequest(DocumentRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('counterparty_id'),
            $request->get('counterparty_agreement_id'),
            $request->get('organization_id'),
            $request->get('storage_id'),
            $request->get('goods'),
            $request->get('comment'),
            $request->get('saleInteger'),
            $request->get('salePercent'),
            $request->get('currency_id'),
        );
    }
}
