<?php

namespace App\Models;

use App\Repositories\Contracts\SoftDeleteInterface;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckingAccount extends DocumentModel implements SoftDeleteInterface
{
    use SoftDeletes, Filterable;

    protected $casts = [
        'date' => 'datetime'
    ];

    protected $fillable = [
        'doc_number',
        'date',
        'organization_id',
        'sum',
        'counterparty_id',
        'counterparty_agreement_id',
        'basis',
        'comment',
        'author_id',
        'organization_bill_id',
        'sender_cash_register_id',
        'employee_id',
        'balance_article_id',
        'operation_type_id',
        'type',
        'active'
    ];

    public function senderCashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'sender_cash_register_id');
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
        return $this->belongsTo(OrganizationBill::class, 'organization_bill_id');
    }

    public function checkingAccount(): BelongsTo
    {
        return $this->belongsTo(OrganizationBill::class, 'checking_account_id');
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

    public static function bootSoftDeletes()
    {

    }

    public function employee() :BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function operationType() :BelongsTo
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }

    public static function filterData(array $data): array
    {
        return [
            'search' => $data['search'] ?? '',
            'sort' => $data['orderBy'] ?? null,
            'direction' => $data['sort'] ?? 'asc',
            'itemsPerPage' => isset($data['itemsPerPage']) ? ($data['itemsPerPage'] == 10 ? 25 : $data['itemsPerPage']) : 25,
            'organization_id' => $data['filterData']['organization_id'] ?? null,
            'author_id' =>  $data['filterData']['author_id'] ?? null,
            'date' => $data['filterData']['date'] ?? null,
            'operation_type' => $data['filterData']['operation_type'] ?? null,
        ];
    }
}
