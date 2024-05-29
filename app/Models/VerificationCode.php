<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    public const MAX_ATTEMPTS = 3;

    protected $fillable = ['user_id', 'code', 'attempts'];

}
