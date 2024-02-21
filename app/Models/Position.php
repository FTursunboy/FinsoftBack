<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Position extends Model
{
    use Searchable;

    protected $fillable = ['name'];

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name
        ];
    }
}
