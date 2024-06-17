<?php

namespace App\DTO\Document;

use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\ServiceRequest;

class ServiceDTO
{
    public function  __construct(public string $date, public int $counterparty_id, public int $counterparty_agreement_id, public int $organization_id,
                     public int $storage_id, public ?array $return_goods, public ?array $sale_goods, public ?string $comment,  public int $currency_id, public ?int $sales_sum,  public ?int $return_sum, public ?int $client_payment, public bool $approve)
    {
    }

    public static function fromRequest(ServiceRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('counterparty_id'),
            $request->get('counterparty_agreement_id'),
            $request->get('organization_id'),
            $request->get('storage_id'),
            $request->get('return_goods'),
            $request->get('sale_goods'),
            $request->get('comment'),
            $request->get('currency_id'),
            $request->get('sales_sum'),
            $request->get('return_sum'),
            $request->get('client_payment'),
            $request->get('approve'),
        );
    }
}
