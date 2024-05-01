<?php

namespace App\Listeners;

use App\Enums\MovementTypes;
use App\Events\DocumentCreated;
use App\Models\CounterpartySettlement;
use App\Models\Document;
use App\Services\HandleDocumentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DocumentListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DocumentCreated $event): void
    {
        (new HandleDocumentCreated($event->document, $event->movementTypes))->handle();
    }
}
