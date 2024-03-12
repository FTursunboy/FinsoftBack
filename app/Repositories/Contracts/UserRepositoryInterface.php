<?php

namespace App\Repositories\Contracts;

use App\DTO\UserDTO;
use App\DTO\UserUpdateDTO;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends IndexInterface
{
    public function index(array $data) :LengthAwarePaginator;

    public function store(UserDTO $DTO);

    public function update(User $user, UserUpdateDTO $DTO);
}
