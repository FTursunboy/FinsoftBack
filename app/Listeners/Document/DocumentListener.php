<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentApprovedEvent;
use App\Services\Document\HandleDocumentApproveCreated;
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
