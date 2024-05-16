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
            'good' => $this->good,
            'income' => (int)$this->income,
            'outcome' => (int)$this->outcome,
            'remainder' => (int)$this->remainder,
            'total' => (int)$this->total,
            'group' => $this->group
        ];
    }
}
