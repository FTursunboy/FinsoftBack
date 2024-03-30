<?php

namespace App\DTO;

use App\Http\Requests\Api\CounterpartyAgreement\CounterpartyAgreementRequest;

class CounterpartyAgreementDTO
{
    public function __construct(public string $name, public string $date, public int $organization_id,
           public int $counterparty_id, public string $contact_person, public int $currency_id, public ?int $payment_id, public ?string $comment, public int $price_type_id)
    {
    }

    public static function fromRequest(CounterpartyAgreementRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('counterparty_id'),
            $request->get('contact_person'),
            $request->get('currency_id'),
            $request->get('payment_id'),
            $request->get('comment'),
            $request->get('price_type_id'),
        );
    }
}
