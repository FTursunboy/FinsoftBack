<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Employee extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use Searchable, SoftDeletes, HasFactory;

    protected $fillable = ['name', 'image', 'position_id', 'phone', 'email', 'address', 'group_id'];

    public static function bootSoftDeletes(){}

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }


    public static function filter(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'name'  => $data['filterData']['name'] ?? null,
            'phone' => $data['filterData']['phone'] ?? null,
            'email' => $data['filterData']['email'] ?? null,
            'address' => $data['filterData']['address'] ?? null,
        ];
    }

    public function hiring(): HasOne
    {
        return $this->hasOne(Hiring::class);
    }


    // В модели Employee
    public function schedule()
    {
        return $this->hasOneThrough(
            Schedule::class,
            Hiring::class,
            'employee_id', // Foreign key on Hiring table...
            'id', // Foreign key on Schedule table...
            'id', // Local key on Employee table...
            'schedule_id'  // Local key on Hiring table...
        );
    }

    public function weekHour() :HasManyThrough
    {
        return $this->hasManyThrough(
            ScheduleWeekHours::class,
            Schedule::class,
            'id', // Foreign key on Schedule table...
            'schedule_id', // Foreign key on ScheduleWeekHour table...
            'id', // Local key on Employee table...
            'id'  // Local key on Schedule table through Hiring
        )->via('schedule');
    }

}
