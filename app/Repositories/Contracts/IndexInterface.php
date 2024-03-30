<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface IndexInterface
{
    public function index(array $data) : LengthAwarePaginator;

}
