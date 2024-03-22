<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounterpartyResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'email'=> $this->email,
            'roles' => $this->roles()->get()->pluck('id'),
            'balance' => 2500,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at
        ];
    }
}
