<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GoodReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'start_reminder' => (int)$this->start_remainder,
            'end_reminder' => (int)$this->end_remainder,
            'remainder' => (int)$this->remainder,
            'income' => (int)$this->income,
            'outcome' => (int)$this->outcome,
            'group' => $this->group,
            'good' => $this->good,
        ];
    }
}
