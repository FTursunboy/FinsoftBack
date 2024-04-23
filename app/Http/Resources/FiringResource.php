<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Firing */
class FiringResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'doc_number' => $this->doc_number,
            'date' => $this->date,
            'organization_id' => $this->organization_id,
            'employee_id' => $this->employee_id,
            'firing_date' => $this->firing_date,
            'basis' => $this->basis,
            'author_id' => $this->author_id,
            'comment' => $this->comment,
        ];
    }
}
