<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ChangeHistory extends Model
{
    protected $fillable = ['document_history_id', 'body'];


    public function changeGoods(): HasMany
    {
        return $this->hasMany(ChangeGoodDocumentHistory::class, 'change_history_id');
    }

}
