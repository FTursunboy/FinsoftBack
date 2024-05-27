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
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
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

        $query = $this->getData($filteredParams);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function getData(array $filteredParams)
    {
        $query = $this->model::whereHas('roles', function ($query) {
            return $query->where('name', '!=', 'admin');
        });

        $query = $this->search($filteredParams['search'], $query);

        $query = $this->filter($query, $filteredParams);

        return $this->sort($filteredParams, $query, ['organization', 'group']);
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
            $query->orWhere('name', 'like', '%' . implode('%', $searchTerm) . '%');
        });
    }

    public function filter($query, array $data)
    {
        return $query->when($data['organization_id'], function ($query) use ($data) {
            return $query->where('organization_id', $data['organization_id']);
        })
            ->when($data['login'], function ($query) use ($data) {
                return $query->where('login', 'like', '%' . $data['login'] . '%');
            })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['email'], function ($query) use ($data) {
                return $query->where('email', 'like', '%' . $data['email'] . '%');
            })
            ->when($data['phone'], function ($query) use ($data) {
                return $query->where('phone', 'like', '%' . $data['phone'] . '%');
            })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->where('deleted_at', '!=', null) : $query->where('deleted_at', null);
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


    public function export(array $data): string
    {
        $filteredParams = $this->model::filter($data);

        $query = $this->model::whereHas('roles', function ($query) {
            $query->where('roles.name', '!=', 'admin');
        });

        $query = $this->search($filteredParams['search'], $query);

        $query = $this->sort($filteredParams, $query, ['organization', 'group']);

        $result = $query->get();

        $filename = 'пользователи ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'ФИО', 'Группа', 'Активность', 'Организация', 'Логин', 'Телефон', 'Почта', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);


        foreach ($result as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->name,
                $row->group->name,
                $row->active ? 'Да' : 'Нет',
                $row->organization->name,
                $row->login,
                $row->phone,
                $row->email,
                $row->deleted_at ? 'Да' : 'Нет',
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;

    }


}
