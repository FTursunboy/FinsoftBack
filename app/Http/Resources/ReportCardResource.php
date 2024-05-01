<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ReportCard */
class ReportCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'date' => $this->date,
            'comment' => $this->comment,
            'employees' => $this->whenLoaded('employees')
        ];
    }
}
