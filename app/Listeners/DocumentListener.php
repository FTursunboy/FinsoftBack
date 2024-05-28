<?php

namespace App\Listeners;

use App\Events\DocumentApprovedEvent;
use App\Services\HandleDocumentApproveCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        (new HandleDocumentApproveCreated($event->document, $event->movementTypes, $event->documentType))->handle();
    }
}
