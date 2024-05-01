<?php

namespace App\Providers;

use App\Events\DocumentCreated;
use App\Listeners\DocumentListener;
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
        DocumentCreated::class => [
            DocumentListener::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Document::observe(DocumentObserver::class);
        MovementDocument::observe(MovementDocumentObserver::class);
        CashStore::observe(CashStoreObserver::class);
        CheckingAccount::observe(CheckingAccountObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
