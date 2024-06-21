<?php

namespace App\DTO;

use App\Http\Requests\Api\Barcode\BarcodeRequest;
use App\Http\Requests\PriceSetUpRequest;

class PriceSetUpDTO
{
    public function __construct(public string $start_date, public int $organization_id,  public ?string $comment, public string $basis, public array $goods) { }

    public static function fromRequest(PriceSetUpRequest $request) :self
    {
        return new static(
            $request->get('start_date'),
            $request->get('organization_id'),
            $request->get('comment'),
            $request->get('basis'),
            $request->get('goods'),
        );
    }
}
