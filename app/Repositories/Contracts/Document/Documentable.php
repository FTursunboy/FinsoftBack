<?php


namespace App\Repositories\Contracts\Document;


use Illuminate\Database\Eloquent\Relations\HasMany;

interface Documentable
{
    public function history() :HasMany;
}
