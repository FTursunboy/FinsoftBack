<?php

namespace App\Models;

use App\Repositories\Contracts\MassOperationInterface;
use App\Repositories\Contracts\SoftDeleteInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model implements SoftDeleteInterface
{
    protected $fillable = ['name', 'deleted_at'];

    public function workerSchedule(): HasMany
    {
        return $this->hasMany(WorkerSchedule::class, 'schedule_id');
    }

    public function weekHours(): HasMany
    {
        return $this->hasMany(ScheduleWeekHours::class, 'schedule_id', 'id');
    }
}
