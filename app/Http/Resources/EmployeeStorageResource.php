<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EmployeeStorageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'from' => Carbon::parse($this->from),
            'to' => Carbon::parse($this->to),
            'employee' => EmployeeResource::make($this->whenLoaded('employee')),
            'deleted_at' => $this->deleted_at
        ];
    }
}
