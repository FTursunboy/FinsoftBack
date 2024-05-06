<?php

namespace App\Repositories\Document;

use App\DTO\Document\SalaryDocumentDTO;
use App\Models\Document;
use App\Models\SalaryDocument;
use App\Models\SalaryDocumentEmployees;
use App\Repositories\Contracts\SalaryDocumentRepositoryInterface;
use App\Traits\DocNumberTrait;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class SalaryDocumentRepository implements SalaryDocumentRepositoryInterface
{
    use FilterTrait, Sort, DocNumberTrait;
    public $model = SalaryDocument::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filteredParams = $this->model::filterData($data);

        $query = $this->model::query();

        $query = $this->search($query, $filteredParams);

        $query = $this->filter($query, $filteredParams);

        $query = $this->sort($filteredParams, $query, ['organization', 'author', 'month']);

        return $query->paginate($filteredParams['itemsPerPage']);
    }

    public function store(SalaryDocumentDTO $DTO)
    {
        $document =  $this->model::create([
            'date' => $DTO->date,
            'month_id' => $DTO->month_id,
            'organization_id' => $DTO->organization_id,
            'comment' => $DTO->comment,
            'doc_number' => $this->uniqueNumber(),
            'author_id' => \Auth::id()
        ]);


        $this->insertDocumentTable($DTO->data,$document);


    }


    private function insertDocumentTable(array $data, SalaryDocument $document)
    {
        $insertArray = array_map(function ($item) use ($document) {
            return [
                'employee_id' => $item['employee_id'],
                'oklad' => $item['oklad'],
                'worked_hours' =>  $item['worked_hours'],
                'salary' =>  $item['salary'],
                'another_payments' =>  $item['another_payments'],
                'takes_from_salary' =>  $item['takes_from_salary'],
                'payed_salary' => $item['payed_salary'] ?? null,
                'salary_document_id' => $document->id,
                'created_at' => Carbon::now()
            ];
        }, $data);

        SalaryDocumentEmployees::insert($insertArray);
    }


    public function search($query, array $data)
    {
        $searchTerm = explode(' ', $data['search']);

        return $query->where(function ($query) use ($searchTerm) {
            $query->where('doc_number', 'like', '%' . implode('%', $searchTerm) . '%')

                ->orWhereHas('author', function ($query) use ($searchTerm) {
                    return $query->where('users.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('organization', function ($query) use ($searchTerm) {
                    return $query->where('organizations.name', 'like', '%' . implode('%', $searchTerm) . '%');
                })
                ->orWhereHas('month', function ($query) use ($searchTerm) {
                    return $query->where('months.name', 'like', '%' . implode('%', $searchTerm) . '%');
                });
        });
    }

    public function filter($query, array $data)
    {
        return $query->when($data['month_id'], function ($query) use ($data) {
                return $query->where('month_id', $data['month_id']);
            })
            ->when($data['date'], function ($query) use ($data) {
                $date = Carbon::createFromFormat('Y-m-d', $data['date'])->format('Y-m-d');
                return $query->where('date', $date);
            })
            ->when($data['author_id'], function ($query) use ($data) {
                return $query->where('author_id', $data['author_id']);
            })
            ->when($data['organization_id'], function ($query) use ($data) {
                return $query->where('organization', $data['organization_id']);
            });
    }

}
