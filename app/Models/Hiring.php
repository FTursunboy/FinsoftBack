<?php

namespace App\Models;

use App\Filters\FiringFilter;
use App\Filters\HiringFilter;
use App\Filters\MovementDocumentFilter;
use App\Repositories\Contracts\SoftDeleteInterface;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hiring extends Model implements SoftDeleteInterface
{
    use SoftDeletes, HasFactory, Filterable;

    protected $fillable = [
        'doc_number',
        'data',
        'employee_id',
        'salary',
        'hiring_date',
        'department_id',
        'basis',
        'position_id',
        'schedule_id',
        'organization_id',
        'comment',
        'author_id'
    ];

    protected $casts = [
        'date' => 'date',
        'hiring_date' => 'date'
    ];

    public function modelFilter()
    {
        return $this->provideFilter(FiringFilter::class);
    }


    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function position() :BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function schedule() :BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
            'employee_id' =>  $data['filterData']['responsible_person_id'] ?? null,
            'position_id' =>  $data['filterData']['author_id'] ?? null,
            'hiring_date' => $date['filterData']['hiring_date'] ?? null,
            'date' => $data['filterData']['date'] ?? null,
        ];
    }
}
