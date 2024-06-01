<?php

namespace App\Providers;

use App\Events\DocumentApprovedEvent;
use App\Events\GoodDocumentHistoryEvent;
use App\Events\MovementApprovedEvent;
use App\Events\SmallRemainderEvent;
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
        GoodDocumentHistoryEvent::class => [
            GoodDocumentHistoryListener::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
     Document::observe(DocumentObserver::class);

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
