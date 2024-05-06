<?php

namespace App\DTO\Document;

use App\Http\Requests\Api\Document\DocumentRequest;
use App\Http\Requests\Api\SalaryDocument\SalaryDocumentRequest;

class SalaryDocumentDTO
{
    public function  __construct(public string $date, public int $organization_id, public int $month_id, public ?string $comment,
                     public array $data)
    {
    }

    public static function fromRequest(SalaryDocumentRequest $request) :self
    {
        return new static(
            $request->get('date'),
            $request->get('organization_id'),
            $request->get('month_id'),
            $request->get('comment'),
            $request->get('data'),


        );
    }
}
