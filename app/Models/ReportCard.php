<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportCard extends Model
{
    use HasFactory;

    protected function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    protected function month(): BelongsTo
    {
        return $this->belongsTo(Month::class);
    }

    protected function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    protected function employees() :HasMany
    {
        return $this->hasMany('report_employees', 'report_card_id', 'id');
    }
}
