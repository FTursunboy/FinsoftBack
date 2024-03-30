<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodImages extends Model
{
    protected $fillable = ['good_id', 'image', 'is_main', 'image_name'];

    public static function filter(array $data): array
    {
        return [
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
        ];
    }
}
