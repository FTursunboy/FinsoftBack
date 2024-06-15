<?php

namespace App\Providers;

use App\Events\CashStore\AccountablePersonEvent;
use App\Events\CashStore\AnotherCashRegisterEvent;
use App\Events\CashStore\BalanceEvent;
use App\Events\CashStore\CashEvent;
use App\Events\CashStore\CounterpartySettlementEvent;
use App\Events\CashStore\CreditEvent;
use App\Events\CashStore\IncomeEvent;
use App\Events\CashStore\InvestmentEvent;
use App\Events\CashStore\OrganizationBillEvent;
use App\Events\DocumentApprovedEvent;
use App\Events\GoodDocumentHistoryEvent;
use App\Events\MovementApprovedEvent;
use App\Events\SmallRemainderEvent;
use App\Listeners\CashStore\AccountabllePersonListener;
use App\Listeners\CashStore\AnotherCashRegisterListener;
use App\Listeners\CashStore\BalanceListener;
use App\Listeners\CashStore\CashListener;
use App\Listeners\CashStore\CounterpartySettlementListener;
use App\Listeners\CashStore\CreditListener;
use App\Listeners\CashStore\IncomeListener;
use App\Listeners\CashStore\InvestmentListener;
use App\Listeners\CashStore\OrganizationBillListener;
use App\Listeners\DocumentListener;
use App\Listeners\GoodDocumentHistoryListener;
use App\Listeners\GoodHistoryListener;
use App\Listeners\MovementListener;
use App\Listeners\SmallRemainderListener;
use App\Models\CashStore;
use App\Models\CheckingAccount;
use App\Models\Document;
use App\Models\GoodDocument;
use App\Models\MovementDocument;
use App\Observers\CashStoreObserver;
use App\Observers\CheckingAccountObserver;
use App\Observers\GoodDocumentObserver;
use App\Observers\InventoryDocumentObserver;
use App\Observers\DocumentObserver;
use App\Observers\MovementDocumentObserver;
use App\Services\HandleMovementDocumentApproveCreated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        DocumentApprovedEvent::class => [
           DocumentListener::class,
           GoodHistoryListener::class
        ],
        SmallRemainderEvent::class => [
            SmallRemainderListener::class
        ],
        MovementApprovedEvent::class => [
            MovementListener::class
        ],
        CashEvent::class => [
            CashListener::class
        ],
        CounterpartySettlementEvent::class => [
            CounterpartySettlementListener::class
        ],
        OrganizationBillEvent::class => [
            OrganizationBillListener::class
        ],
        AnotherCashRegisterEvent::class => [
            AnotherCashRegisterListener::class
        ],
        InvestmentEvent::class => [
            InvestmentListener::class
        ],
        CreditEvent::class => [
            CreditListener::class
        ],
        AccountablePersonEvent::class => [
            AccountabllePersonListener::class
        ],
        IncomeEvent::class => [
            IncomeListener::class
        ],
        BalanceEvent::class => [
            BalanceListener::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
     Document::observe(DocumentObserver::class);
     GoodDocument::observe(GoodDocumentObserver::class);

       MovementDocument::observe(MovementDocumentObserver::class);
   //   CashStore::observe(CashStoreObserver::class);
//       CheckingAccount::observe(CheckingAccountObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
