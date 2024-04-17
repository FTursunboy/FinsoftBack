<?php


namespace App\Repositories\Contracts;


use Illuminate\Database\Eloquent\Relations\HasMany;

interface Documentable
{
    public function history() :HasMany;
}
