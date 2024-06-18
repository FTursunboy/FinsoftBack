<?php

namespace App\Listeners\CashStore;

use App\Events\CashStore\CashEvent;
use App\Services\CashStore\CashService;


class CashListener
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
    public function handle(CashEvent $event): void
    {
        (new CashService($event->cashStore, $event->type))->handle();
    }
}
