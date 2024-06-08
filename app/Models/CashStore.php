<?php

namespace App\Models;

use App\Filters\CashStoreFilter;
use App\Filters\InventoryDocumentFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use function Laravel\Prompts\password;

class CashStore extends DocumentModel
{
    use SoftDeletes, HasFactory, Filterable;

    protected $casts = [
        'date' => 'datetime',
        'active' => 'boolean'
    ];

    protected $fillable = [
        'doc_number',
        'date',
        'organization_id',
        'cashRegister_id',
        'sum',
        'counterparty_id',
        'counterparty_agreement_id',
        'basis',
        'comment',
        'author_id',
        'organizationBill_id',
        'senderCashRegister_id',
        'employee_id',
        'balance_article_id',
        'operationType_id',
        'type',
        'month_id',
        'active',
        'sender',
        'recipient'
    ];


    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cashRegister_id');
    }

    public function senderCashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'senderCashRegister_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function organizationBill(): BelongsTo
    {
        return $this->belongsTo(OrganizationBill::class, 'organizationBill_id');
    }

    public function balanceArticle(): BelongsTo
    {
        return $this->belongsTo(BalanceArticle::class, 'balance_article_id');
    }

    public function modelFilter()
    {
        return $this->provideFilter(CashStoreFilter::class);
    }

    public function month(): BelongsTo
    {
        return $this->belongsTo(Month::class, 'month_id');
    }

    public function author() :BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function counterparty() :BelongsTo
    {
        return $this->belongsTo(Counterparty::class, 'counterparty_id');
    }

    public function counterpartyAgreement() :BelongsTo
    {
        return $this->belongsTo(CounterpartyAgreement::class, 'counterparty_agreement_id');
    }

    public function employee() :BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function operationType() :BelongsTo
    {
        return $this->belongsTo(OperationType::class, 'operationType_id');
    }

    public function responsiblePerson(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'responsible_person_id');
    }

    public static function filterData(array $data): array
    {

        $filteredData = [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'responsible_person_id' =>  $data['responsible_person_id'] ?? null,
            'date' => $data['date'] ?? null,
            'operation_type_id' => $data['operation_type_id'] ?? null,
            'author_id' => $data['author_id'] ?? null,
            'month_id' => $data['month_id'] ?? null,
            'employee_id' => $data['employee_id'] ?? null,
            'counterparty_agreement_id' => $data['counterparty_agreement_id'] ?? null,
            'counterparty_id' => $data['counterparty_id'] ?? null,
            'balance_article_id' => $data['balance_article_id'] ?? null,
            'organization_bill_id' => $data['organization_bill_id'] ?? null,
            'currency_id' => $data['currency_id'] ?? null,
            'organization_id' => $data['organization_id'] ?? null,
            'sender_cash_register_id' => $data['sender_cash_register_id'] ?? null,
            'cash_register_id' => $data['cash_register_id'] ?? null,
            'active' => $data['active'] ?? null,
            'deleted' => $data['deleted'] ?? null,
        ];

        if (isset($data['filterData'])) {
            $filteredData['organization_id'] = $data['filterData']['organization_id'] ?? $filteredData['organization_id'];
            $filteredData['responsible_person_id'] = $data['filterData']['responsible_person_id'] ?? $filteredData['responsible_person_id'];
            $filteredData['author_id'] = $data['filterData']['author_id'] ?? $filteredData['author_id'];
            $filteredData['date'] = $data['filterData']['date'] ?? $filteredData['date'];
            $filteredData['operation_type_id'] = $data['filterData']['operation_type_id'] ?? $filteredData['operation_type_id'];
            $filteredData['active'] = $data['filterData']['active'] ?? $filteredData['active'];
            $filteredData['deleted'] = $data['filterData']['deleted'] ?? $filteredData['deleted'];
        }

        return $filteredData;
    }

}
