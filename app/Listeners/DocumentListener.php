<?php

namespace App\Listeners;

use App\Enums\MovementTypes;
use App\Events\DocumentApprovedEvent;
use App\Models\CounterpartySettlement;
use App\Models\Document;
use App\Services\HandleDocumentApproveCreated;
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
    public function handle(DocumentApprovedEvent $event): void
    {
        (new HandleDocumentApproveCreated($event->document, $event->movementTypes))->handle();
    }
}
