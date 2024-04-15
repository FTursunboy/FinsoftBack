<?php

namespace App\Models;

use App\Filters\MovementDocumentFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MovementDocument extends Model
{
    use SoftDeletes, HasFactory, Filterable;

    protected $fillable = [
        'uuid',
        'doc_number',
        'date',
        'organization_id',
        'sender_storage_id',
        'recipient_storage_id',
        'author_id',
        'comment',
    ];




    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;



    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public static function bootSoftDeletes()
    {

    }

    public function author() :BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function senderStorage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'sender_storage_id');
    }

    public function recipientStorage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'recipient_storage_id');
    }

    public function goods(): HasMany
    {
        return $this->hasMany(Good::class, 'document_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function modelFilter()
    {
        return $this->provideFilter(MovementDocumentFilter::class);
    }


    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'orderBy' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'recipientStorage_id' => $data['filterData']['recipientStorage_id'] ?? null,
            'senderStorage_id' => $data['filterData']['senderStorage_id'] ?? null,
            'organization_id' =>  $data['filterData']['organization_id'] ?? null,
            'author_id' =>  $data['filterData']['author_id'] ?? null,
             'date' => $data['filterData']['date'] ?? null,
        ];
    }
}
