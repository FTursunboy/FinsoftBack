<?php

namespace App\Listeners\Document;

use App\Services\Document\Equipment\GoodAccountingService;


class EquipmentListener
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
        (new GoodAccountingService($event->document, $event->documentType))->handle();
    }
}
