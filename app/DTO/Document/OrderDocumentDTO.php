<?php

namespace App\DTO\Document;

use App\Http\Requests\Api\OrderDocument\OrderDocumentRequest;

class OrderDocumentDTO
{
    public function  __construct(public string $date, public int $counterparty_id, public int $counterparty_agreement_id, public int $organization_id,
                     public ?int $order_status_id, public ?array $goods, public ?string $comment, public float $summa, public ?string $shipping_date, public int $currency_id)
    {
    }

    public static function fromRequest(OrderDocumentRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('counterparty_id'),
            $request->get('counterparty_agreement_id'),
            $request->get('organization_id'),
            $request->get('order_status_id'),
            $request->get('goods'),
            $request->get('comment'),
            $request->get('summa'),
            $request->get('shipping_date'),
            $request->get('currency_id'),

        );
    }
}
