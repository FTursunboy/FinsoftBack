<?php

namespace App\Listeners\CashStore;

use App\Services\CashStore\IncomeService;


class IncomeListener
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
        (new IncomeService($event->cashStore, $event->type))->handle();
    }
}
