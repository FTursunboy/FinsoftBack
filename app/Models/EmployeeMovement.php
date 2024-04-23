<?php

namespace App\Models;

use App\Filters\EmployeeMovementFilter;
use App\Filters\HiringFilter;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeMovement extends Model
{
    use SoftDeletes, HasFactory, Filterable;

    protected $fillable = [
        'doc_number',
        'date',
        'employee_id',
        'salary',
        'position_id',
        'movement_date',
        'department_id',
        'schedule',
        'basis',
        'organization_id',
        'comment',
        'author_id'
    ];

    protected $casts = [
        'date' => 'date',
        'hiring_date' => 'date'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function modelFilter()
    {
        return $this->provideFilter(EmployeeMovementFilter::class);
    }

    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'orderBy' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'name'  => $data['filterData']['name'] ?? null,
            'date' => $data['filterData']['date'] ?? null,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
            'employee_id' =>$data['filterData']['employee_id'] ?? null,
            'department_id' => $data['filterData']['department_id'] ?? null,
            'position_id' => $data['filterData']['position_id']  ?? null ,
            'movement_date' => $data['filterData']['movement_date'] ?? null
        ];
    }
}
