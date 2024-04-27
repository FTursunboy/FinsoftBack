<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = ['name', 'deleted_at'];

    public function workerSchedule(): HasMany
    {
        return $this->hasMany(WorkerSchedule::class, 'schedule_id');
    }
}
