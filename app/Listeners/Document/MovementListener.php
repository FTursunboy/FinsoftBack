<?php

namespace App\Listeners\Document;

use App\Services\Document\HandleMovementDocumentApproveCreated;

class MovementListener
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
    public function handle(object $event): void
    {
        (new HandleMovementDocumentApproveCreated($event->document, $event->movementTypes, $event->documentType, $event->storageId))->handle();
    }
}
