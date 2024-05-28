<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleWeekHours extends Model
{
    protected $table = 'schedule_week_hours';

    protected $fillable = ['week', 'hours', 'schedule_id'];

    public $timestamps = false;
}
