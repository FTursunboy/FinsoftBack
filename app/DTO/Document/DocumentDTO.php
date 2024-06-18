<?php

namespace App\DTO\Document;

use App\Http\Requests\Api\Document\DocumentRequest;

class DocumentDTO
{
    public function  __construct(public string $date, public int $counterparty_id, public int $counterparty_agreement_id, public int $organization_id,
                     public int $storage_id, public ?array $goods, public ?string $comment, public ?int $saleInteger, public ?int $salePercent, public int $currency_id, public ?int $sum)
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
            $request->get('sum'),
        );
    }

    public static function fromServiceDTO(ServiceDTO $serviceDTO, array $goods) :self
    {
        return new static(
            $serviceDTO->date,
            $serviceDTO->counterparty_id,
            $serviceDTO->counterparty_agreement_id,
            $serviceDTO->organization_id,
            $serviceDTO->storage_id,
            $goods,
            $serviceDTO->comment,
            null,
            null,
            $serviceDTO->currency_id,
            null
        );
    }
}
