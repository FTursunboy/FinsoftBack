<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DocumentHistory extends Model
{
    protected $fillable = ['document_id', 'status', 'user_id'];


    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function changes(): HasMany
    {
        return $this->hasMany(ChangeHistory::class)->orderBy('created_at', 'desc');
    }
}
