<?php

namespace App\Repositories;

use App\DTO\BarcodeDTO;
use App\Models\Barcode;
use App\Models\Employee;
use App\Models\Good;
use App\Models\Hiring;
use App\Models\Month;
use App\Models\Organization;
use App\Models\ReportCard;
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

    public $model = ReportCard::class;

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


    public function getEmployees(array $data)
    {
        $filterParams = $this->model::filterData($data);

        $currentYear = now()->year;  // Получаем текущий год

        $month = (int)$data['month_id'];

        return Employee::query()
            ->select([
                'employees.id',
                'employees.name',
                'ws.number_of_hours',
                \DB::raw("CASE
            WHEN firings.firing_date IS NOT NULL AND MONTH(firings.firing_date) = $month THEN
                DATEDIFF(firings.firing_date, DATE_FORMAT(CONCAT(YEAR(hirings.hiring_date), '-', MONTH(hirings.hiring_date), '-01'), '%Y-%m-%d')) * sc.hours_per_day
            ELSE
                ws.number_of_hours
        END AS adjusted_hours")
            ])
            ->join('hirings', 'hirings.employee_id', '=', 'employees.id')
            ->join('schedules as sc', 'sc.id', '=', 'hirings.schedule_id')
            ->join('worker_schedules as ws', 'ws.schedule_id', '=', 'sc.id')
            ->leftJoin('firings', function ($join) use ($month, $currentYear) {
                $join->on('firings.employee_id', '=', 'hirings.employee_id')
                    ->where('firings.organization_id', '=', 'hirings.organization_id')
                    ->whereMonth('firings.firing_date', '=', $month)
                    ->whereYear('firings.firing_date', '=', $currentYear);
            })
            ->where('ws.month_id', $month)
            ->where('hirings.organization_id', $filterParams['organization_id'])
            ->whereMonth('hirings.hiring_date', '<=', $filterParams['month_id'])
            ->whereYear('hirings.hiring_date', '=', $currentYear)
            ->paginate($filterParams['itemsPerPage']);




    }
}
