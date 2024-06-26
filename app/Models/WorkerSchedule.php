<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkerSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = ['schedule_id', 'month_id', 'number_of_hours'];

    public function month(): BelongsTo
    {
        return $this->belongsTo(Month::class, 'month_id');
    }
}
