<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class EmployeeStorage extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'storage_id',
        'employee_id',
        'from',
        'to'
    ];

    public function employee(): belongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function toSearchableArray() :array
    {
        return [
            'name' => $this->name
        ];
    }

    public static function bootSoftDeletes()
    {

    }

}
