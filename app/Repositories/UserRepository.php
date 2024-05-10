<?php

namespace App\Repositories;

use App\DTO\FcmTokenDTO;
use App\DTO\UserDTO;
use App\DTO\UserUpdateDTO;
use App\Models\User;
use App\Models\UserFcmToken;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class UserRepository implements UserRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = User::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->model::whereHas('roles', function ($query) {
            $query->where('roles.name', '!=', 'admin');
        });

        $query = $this->search($filteredParams['search'], $query);

        $query = $this->sort($filteredParams, $query, ['organization', 'group']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(UserDTO $DTO)
    {
        $image = $DTO->image ? Storage::disk('public')->put('userPhoto', $DTO->image) : null;

        $user = $this->model::create([
            'name' => $DTO->name,
            'organization_id' => $DTO->organization_id,
            'login' => $DTO->login,
            'password' => $DTO->password,
            'phone' => $DTO->phone,
            'email' => $DTO->email,
            'image' => $image,
            'group_id' => $DTO->group_id
        ])->assignRole('user');
    }


    public function update(User $user, UserUpdateDTO $DTO)
    {
        if ($DTO->image != null) {
            $image = Storage::disk('public')->put('userPhoto', $DTO->image);
            Storage::delete('public/' . $user->image);
        }

        $user->update([
            'name' => $DTO->name,
            'organization_id' => $DTO->organization_id,
            'login' => $DTO->login,
            'phone' => $DTO->phone,
            'email' => $DTO->email,
            'image' => $image ?? $user->image,
            'status' => $DTO->status,
            'group_id' => $DTO->group_id
        ]);

        return $user->load('organization');
    }


    public function deleteImage(User $user)
    {
        Storage::delete('public/' . $user->image);
        $user->update(['image' => null]);
    }

    public function search(string $search, $query)
    {
        $searchTerm = explode(' ', $search);

        return $query->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');
        });

    }

    public function documentAuthors(array $data)
    {
        $filteredParams = $this->model::filter($data);

        $query = User::whereHas('documents');

        $query = $this->search($filteredParams['search'], $query);

        $query = $this->sort($filteredParams, $query);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function addFcmToken(FcmTokenDTO $DTO)
    {
        UserFcmToken::create([
            'fcm_token' => $DTO->fcm_token,
            'device' => $DTO->device,
            'user_id' => auth()->id()
        ]);
    }
}
