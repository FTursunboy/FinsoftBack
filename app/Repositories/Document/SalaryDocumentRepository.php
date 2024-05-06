<?php

namespace App\Repositories\Document;

use App\DTO\Document\SalaryDocumentDTO;
use App\Models\Document;
use App\Models\SalaryDocument;
use App\Models\SalaryDocumentEmployees;
use App\Repositories\Contracts\SalaryDocumentRepositoryInterface;
use App\Traits\DocNumberTrait;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class SalaryDocumentRepository implements SalaryDocumentRepositoryInterface
{
    use DocNumberTrait;
    public $model = SalaryDocument::class;

    public function index(array $data): LengthAwarePaginator
    {

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
}
