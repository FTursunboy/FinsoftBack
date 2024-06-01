<?php

namespace App\Listeners;

use App\Services\HandleMovementDocumentApproveCreated;

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
