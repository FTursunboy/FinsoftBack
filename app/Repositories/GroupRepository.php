<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\DTO\GroupDTO;
use App\Models\Barcode;
use App\Models\Employee;
use App\Models\Group;
use App\Models\Storage;
use App\Models\User;
use App\Repositories\Contracts\GroupRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Pagination\LengthAwarePaginator;

class GroupRepository implements GroupRepositoryInterface
{

    public $model = Group::class;
    use Sort, FilterTrait;

    public function usersGroup(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $userQuery = User::query();

        $userQuery = $this->filterUser($userQuery, $filterParams);
        $userIds = $this->searchUsers($userQuery, $filterParams['search'])->pluck('id')->toArray();


        $query = Group::where('type', Group::USERS)
            ->whereHas('users', function ($query) use ($userIds) {
                $query->whereIn('users.id', $userIds);
            })
            ->with(['users' => function ($query) use ($userIds) {
                $query->whereIn('users.id', $userIds)->with('organization', 'group');
            }]);

        $query = $this->sort($filterParams, $query, []);

        $groups = $query->paginate($filterParams['itemsPerPage']);

        foreach ($groups as $group) {
            $filteredUsers = $group->users->filter(function ($user) use ($userIds) {
                return in_array($user->id, $userIds);
            });
            $group->setRelation('users', $filteredUsers);
        }

        return $groups;
    }

    public function filterUser($query, array $data)
    {
        return $query
            ->when($data['organization_id'], function ($query) use ($data) {
                return $query->where('users.organization_id', $data['organization_id']);
            })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['login'], function ($query) use ($data) {
                return $query->where('login', 'like', '%' . $data['login'] . '%');
            })
            ->when($data['email'], function ($query) use ($data) {
                return $query->where('email', 'like', '%' . $data['email'] . '%');
            })
            ->when($data['phone'], function ($query) use ($data) {
                return $query->where('phone', 'like', '%' . $data['phone'] . '%');
            })
            ->when($data['group_id'], function ($query) use ($data) {
                return $query->where('users.group_id', $data['group_id']);
            })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->whereNotNull('deleted_at') : $query->whereNull('deleted_at');
            });
    }

    public function searchUsers($query, string $search)
    {
        $searchTerm = explode(' ', $search);

        return $query->where(function ($query) use ($searchTerm) {
            return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhere('email', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhere('login', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhere('phone', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('group', function ($query) use ($searchTerm) {
                    return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ;
        });
    }

    public function searchEmployees($query, string $search)
    {
        $searchTerm = explode(' ', $search);

        return $query->where(function ($query) use ($searchTerm) {
            return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhere('email', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhere('address', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('group', function ($query) use ($searchTerm) {
                    return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('position', function ($query) use ($searchTerm) {
                    return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ;
        });
    }

    public function searchStorages($query, string $search)
    {
        $searchTerm = explode(' ', $search);

        return $query->where(function ($query) use ($searchTerm) {
            return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%')
                ->orWhereHas('group', function ($query) use ($searchTerm) {
                    return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ;
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
            ->when($data['email'], function ($query) use ($data) {
                return $query->where('email', 'like', '%' . $data['email'] . '%');
            })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['phone'], function ($query) use ($data) {
                return $query->where('phone', 'like', '%' . $data['phone'] . '%');
            })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->where('deleted_at', null) : $query->where('deleted_at', '!=', null);
            });
    }


    public function storagesGroup(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $storageQuery = Storage::query();

        $storageGroup = $this->filterStorages($storageQuery, $filterParams);
        $storageIds = $this->searchStorages($storageGroup, $filterParams['search'])->pluck('id')->toArray();


        $query = Group::where('type', Group::STORAGES)
            ->whereHas('storages', function ($query) use ($storageIds) {
                $query->whereIn('storages.id', $storageIds);
            })
            ->with(['storages' => function ($query) use ($storageIds) {
                $query->whereIn('storages.id', $storageIds)->with('organization', 'group');
            }]);


        $groups = $query->paginate($filterParams['itemsPerPage']);

        foreach ($groups as $group) {
            $filteredUsers = $group->storages->filter(function ($storage) use ($storageIds) {
                return in_array($storage->id, $storageIds);
            });
            $group->setRelation('storages', $filteredUsers);
        }

        return $groups;
    }


    public function employeesGroup(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $employeeGroup = Employee::query();

        $employeeGroup = $this->filterEmployee($employeeGroup, $filterParams);
        $employeeIds = $this->searchEmployees($employeeGroup, $filterParams['search'])->pluck('id')->toArray();


        $query = Group::where('type', Group::EMPLOYEES)
            ->whereHas('employees', function ($query) use ($employeeIds) {
                $query->whereIn('employees.id', $employeeIds);
            })
            ->with(['employees' => function ($query) use ($employeeIds) {
                $query->whereIn('employees.id', $employeeIds)->with('position', 'group');
            }]);


        $query = $this->sort($filterParams, $query, []);

        $groups = $query->paginate($filterParams['itemsPerPage']);

        foreach ($groups as $group) {
            $filteredUsers = $group->employees->filter(function ($employee) use ($employeeIds) {
                return in_array($employee->id, $employeeIds);
            });
            $group->setRelation('employees', $filteredUsers);
        }

        return $groups;
    }


    public function getUsers(Group $group, array $data): LengthAwarePaginator
    {
        $filterParams = User::filter($data);

        $query = $this->getUsersQuery($group, $filterParams);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function getUsersQuery(Group $group, array $filterParams)
    {
        $query = User::where('group_id', $group->id)
            ->whereHas('roles', function ($query) {
                return $query->where('name', '!=', 'admin');
            });

        $query = $this->searchUser($query, $filterParams);

        $query = $this->filterUser($query, $filterParams);

        return $this->sort($filterParams, $query, ['organization']);
    }

    public function getStorages(Group $group, array $data): LengthAwarePaginator
    {
        $filterParams = Storage::filter($data);

        $query = $this->getStoragesQuery($group, $filterParams);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function getStoragesQuery(Group $group, array $filterParams)
    {
        $query = Storage::where('group_id', $group->id);

        $query = $this->search($query, $filterParams);

        $query = $this->filterStorage($query, $filterParams);

        return $this->sort($filterParams, $query, ['employeeStorage', 'organization']);
    }


    public function getEmployees(Group $group, array $data): LengthAwarePaginator
    {
        $filterParams = Employee::filter($data);

        $query = $this->getEmployeesQuery($group, $filterParams);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function getEmployeesQuery(Group $group, array $filterParams)
    {
        $query = Employee::where('group_id', $group->id);

        $query = $this->search($query, $filterParams);

        $query = $this->filterEmployee($query, $filterParams);

        return $this->sort($filterParams, $query, ['position', 'group']);
    }

    public function store(GroupDTO $DTO)
    {
        return Group::create([
            'name' => $DTO->name,
            'type' => $DTO->type
        ]);
    }

    public function update(Group $group, GroupDTO $DTO): Group
    {
        $group->update([
            'name' => $DTO->name,
        ]);

        return $group;
    }


    public function search($query, array $data)
    {
        return $query->where('name', 'like', '%' . $data['search'] . '%');
    }

    public function searchUser($query, array $data)
    {
        $searchTerm = explode(' ', $data['search']);

        return $query->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . implode('%', $searchTerm) . '%');
        });
    }


    public function filterStorage($query, array $data)
    {
        return $query->when($data['organization_id'], function ($query) use ($data) {
            return $query->where('organization_id', $data['organization_id']);
        })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['employee_id'], function ($query) use ($data) {
                return $query->join('employee_storages', 'employee_storages.storage_id', '=', 'storages.id')
                    ->where([
                        ['employee_storages.employee_id', $data['employee_id']],
                    ])
                    ->select('storages.id', 'storages.name', 'storages.organization_id', 'storages.created_at', 'storages.deleted_at');
            });
    }


    public function filterEmployee($query, array $data)
    {
        return $query
            ->when($data['position_id'], function ($query) use ($data) {
                return $query->where('employees.position_id', $data['position_id']);
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
            ->when($data['group_id'], function ($query) use ($data) {
                return $query->where('employees.group_id', $data['group_id']);
            })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->whereNotNull('deleted_at') : $query->whereNull('deleted_at');
            });
    }

    public function filterStorages($query, array $data)
    {
        return $query
            ->when($data['organization_id'], function ($query) use ($data) {
                return $query->where('storages.organization_id', $data['organization_id']);
            })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', '%' . $data['name'] . '%');
            })
            ->when($data['group_id'], function ($query) use ($data) {
                return $query->where('employees.group_id', $data['group_id']);
            })
            ->when(isset($data['deleted']), function ($query) use ($data) {
                return $data['deleted'] ? $query->whereNotNull('deleted_at') : $query->whereNull('deleted_at');
            });
    }


    public function exportEmployees(Group $group, array $data): string
    {
        $filterParams = Employee::filter($data);

        $query = $this->getEmployeesQuery($group, $filterParams);

        $result = $query->get();

        $filename = 'report ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'ФИО', 'Должность', 'Телефон', 'Почта', 'Адрес', 'Группа', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);


        foreach ($result as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->name,
                $row->position?->name,
                $row->phone,
                $row->email,
                $row->address,
                $row->group?->name,
                $row->deleted_at ? 'Да' : 'Нет',
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;
    }

    public function exportUsers(Group $group, array $data): string
    {
        $filterParams = User::filter($data);

        $query = $this->getUsersQuery($group, $filterParams);

        $result = $query->get();

        $filename = 'report ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'ФИО', 'Логин', 'Почта', 'Телефон', 'Группа', 'Организация', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);


        foreach ($result as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->name,
                $row->login,
                $row->email,
                $row->phone,
                $row->group?->name,
                $row->organization?->name,
                $row->deleted_at ? 'Да' : 'Нет',
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;
    }

    public function exportStorages(Group $group, array $data): string
    {
        $filterParams = Storage::filter($data);

        $query = $this->getStoragesQuery($group, $filterParams);

        $result = $query->get();

        $filename = 'report ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'ФИО', 'Организация', 'Группа', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);


        foreach ($result as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->name,
                $row->organization?->name,
                $row->group?->name,
                $row->deleted_at ? 'Да' : 'Нет',
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;

    }

}
