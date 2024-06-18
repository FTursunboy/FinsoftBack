<?php

namespace App\Listeners\CashStore;

use App\Services\CashStore\OrganizationBillService;

class OrganizationBillListener
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
        (new OrganizationBillService($event->cashStore, $event->type))->handle();
    }
}
