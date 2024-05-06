<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryDocument extends DocumentModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'doc_number',
        'date',
        'organization_id',
        'month_id',
        'author_id',
        'comment',
        'active',
    ];


    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function organization() :BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function month(): BelongsTo
    {
        return $this->belongsTo(Month::class);
    }

}
