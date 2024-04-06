<?php

namespace App\Models;

use App\Enums\DocumentHistoryStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodDocument extends Model
{

    use SoftDeletes;


    protected $guarded = false;

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }



}

