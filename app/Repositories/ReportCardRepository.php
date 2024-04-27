<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Employee;
use App\Models\Good;
use App\Models\Hiring;
use App\Models\Month;
use App\Models\Organization;
use App\Repositories\Contracts\BarcodeRepositoryInterface;
use App\Repositories\Contracts\ReportCardRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Collection\Collection;
use function PHPUnit\Framework\isFalse;

class ReportCardRepository implements ReportCardRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Barcode::class;

    public function store(BarcodeDTO $DTO) :Barcode
    {
        return Barcode::create([
            'barcode' => $DTO->barcode,
            'good_id' => $DTO->good_id
        ]);
    }

    public function update(Barcode $barcode, BarcodeDTO $DTO) :Barcode
    {
        $barcode->update([
            'barcode' => $DTO->barcode,
            'good_id' => $DTO->good_id
        ]);

        return $barcode;
    }

    public function delete(Barcode $barcode)
    {
        $barcode->delete();
    }

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = $this->search($filterParams['search']);

        $query = $query->where('good_id', $good->id);

        $query = $this->sort($filterParams, $query, []);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function search(string $search)
    {
        $searchTerm = explode(' ', $search);

        return $this->model::where('barcode', 'like', '%' . implode('%', $searchTerm) . '%');
    }


    public function getEmployees(Organization $organization, Month $month)
    {
        $employees = Employee::whereIn('id', function ($query) use ($organization, $month) {
            $query->select('employee_id')
                ->from('hirings')
                ->where('organization_id', $organization->id)
                ->whereMonth('hire_date', '>=', $month);
        })->get();

        return $employees;
    }


}
