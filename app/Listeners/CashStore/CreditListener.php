<?php

namespace App\Listeners\CashStore;

use App\Services\CashStore\CreditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreditListener
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
        (new CreditService($event->cashStore, $event->type))->handle();
    }
}
