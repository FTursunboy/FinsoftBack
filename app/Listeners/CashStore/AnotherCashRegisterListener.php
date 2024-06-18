<?php

namespace App\Listeners\CashStore;

use App\Services\CashStore\AnotherCashRegisterService;


class AnotherCashRegisterListener
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
        (new AnotherCashRegisterService($event->cashStore, $event->type, $event->cashRegisterId))->handle();
    }
}
