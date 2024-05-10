<?php

namespace App\Models;

use App\Repositories\Contracts\Document\Documentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

abstract class DocumentModel extends Model implements Documentable
{
    use SoftDeletes;

    protected $casts = ['active' => 'bool' ];

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public function history(): HasMany
    {
        return $this->hasMany(DocumentHistory::class, 'document_id')->orderBy('created_at');
    }


    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }



}
