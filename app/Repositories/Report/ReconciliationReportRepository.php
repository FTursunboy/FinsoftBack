<?php

namespace App\Repositories\Report;

use App\Enums\MovementTypes;
use App\Models\Counterparty;
use App\Models\CounterpartySettlement;
use App\Repositories\Contracts\Report\ReconciliationReportRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReconciliationReportRepository implements ReconciliationReportRepositoryInterface
{
    use Sort, FilterTrait;

    protected $model = CounterpartySettlement::class;

    public function index(Counterparty $counterparty, array $data): LengthAwarePaginator
    {
        $data = $this->model::filterData($data);

        $query = $this->model::query()->where('counterparty_id', $counterparty->id);

        if ($data['from'] != null) {
            $query->where([
                ['date', '>=', $data['from']],
                ['date', '<=', $data['to']]
            ]);
        }

        $query = $query->filter($data);

        $query = $this->sort($data, $query, ['goodAccounting', 'goodAccounting.good', 'counterparty', 'counterpartyAgreement', 'organization']);

        return $query->paginate($data['itemsPerPage']);
    }

    public function debts(Counterparty $counterparty, array $data)
    {
        $data = $this->model::filterData($data);

        $query = $this->model::query()
            ->where('counterparty_id', $counterparty->id);

        $query = $this->getDebts($query, $data['from'], $data['to']);

        return $query->get();
    }

    public function getDebts($query, string $from, string $to)
    {
        $outcome = MovementTypes::Outcome->value;
        $income = MovementTypes::Income->value;

        return $query->select('counterparty_settlements.counterparty_id',
            DB::raw("SUM(CASE WHEN movement_type = '$income' and date < '$from' THEN sum ELSE 0 END) -
                    SUM(CASE WHEN movement_type = '$outcome' and date < '$from' THEN sum ELSE 0 END) as debt_at_begin"),
            DB::raw("SUM(CASE WHEN movement_type = '$income' and date >= '$from'and date <= '$to' THEN sum ELSE 0 END) -
                    SUM(CASE WHEN movement_type = '$outcome' and date >= '$from' and date <= '$to' THEN sum ELSE 0 END) -
                    (SUM(CASE WHEN movement_type = '$income' and date < '$from' and date <= '$to'THEN sum ELSE 0 END) -
                    SUM(CASE WHEN movement_type = '$outcome' and date < '$from' and date <= '$to'THEN sum ELSE 0 END)) as debt_at_end
                    ")
        )->groupBy('counterparty_settlements.counterparty_id');
    }

    public function export(Counterparty $counterparty, array $data): string
    {
        $query = $this->model::query();

        $filterData = $this->model::filterData($data);

        if ($filterData['from'] != null) {
            $query->where([
                ['date', '>=', $filterData['from']],
                ['date', '<=', $filterData['to']]
            ]);
        }

        $query = $query->where('counterparty_settlements.counterparty_id', $counterparty->id)
            ->select([
                'c.name as counterparty',
                'movement_type',
                'cA.name as counterpartyAgreement',
                'cur.name as currency',
                'o.name as organization',
                'sale_sum',
                'sum',
                'counterparty_settlements.date as date'
            ])
            ->join('counterparties as c', 'counterparty_settlements.counterparty_id', '=', 'c.id')
            ->join('counterparty_agreements as cA', 'counterparty_settlements.counterparty_agreement_id', '=', 'cA.id')
            ->join('currencies as cur', 'counterparty_settlements.currency_id', '=', 'cur.id')
            ->join('organizations as o', 'counterparty_settlements.organization_id', '=', 'o.id');

        $result = $query->filter($filterData)->get();

        $filename = 'report ' . now() . '.xlsx';

        $filePath = storage_path($filename);
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        $headerRow = WriterEntityFactory::createRowFromArray([
            'Дата', 'Поставщик', 'Тип', 'Договор', 'Организация', 'Сумма со скидкой', 'Сумма', 'Валюта', 'Помечен на удаление'
        ]);

        $writer->addRow($headerRow);


        foreach ($result as $row) {
            $dataRow = WriterEntityFactory::createRowFromArray([
                $row->date,
                $row->counterparty,
                $row->movement_type,
                $row->counterpartyAgreement,
                $row->organization,
                $row->sale_sum,
                $row->sum,
                $row->currency,
                $row->deleted_at ? 'Да' : 'Нет',
            ]);
            $writer->addRow($dataRow);
        }

        $writer->close();

        return $filePath;

    }

}
