<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Counterparty extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'email'];

    use Searchable, SoftDeletes;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'counterparty_roles', 'counterparty_id', 'role_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'email' => $this->email,
        ];
    }
}
