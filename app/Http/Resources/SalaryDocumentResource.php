<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\SalaryDocument */
class SalaryDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'date' => $this->date,
            'organization' => $this->whenLoaded('organization'),
            'month' => $this->whenLoaded('month'),
            'author' => $this->whenLoaded('author'),
            'comment' => $this->comment,
            'employees' =>  SalaryDocumentEmployeeResource::collection($this->whenLoaded('employees'))
        ];
    }
}
