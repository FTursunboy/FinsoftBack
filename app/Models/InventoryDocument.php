<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class InventoryDocument extends Model implements \App\Repositories\Contracts\SoftDeleteInterface
{
    protected $fillable = ['doc_number', 'date', 'organization_id', 'storage_id', 'responsible_person_id', 'author_id', 'comment'];

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $casts = [
        'active' => 'bool'
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public static function bootSoftDeletes()
    {

    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function responsiblePerson(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'responsible_person_id');
    }

}
