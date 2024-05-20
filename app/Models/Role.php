<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = false;

    protected $table = 'user_roles';

    public const CLIENT = 'Клиент';
    public const SUPPLIER = 'Поставщик';
    public const OTHER = 'Прочие';

}
