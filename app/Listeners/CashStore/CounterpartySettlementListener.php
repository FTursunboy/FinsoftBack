<?php

namespace App\Listeners\CashStore;

use App\Services\CashStore\CounterpartySettlementService;

class CounterpartySettlementListener
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
        (new CounterpartySettlementService($event->cashStore, $event->type))->handle();
    }
}
