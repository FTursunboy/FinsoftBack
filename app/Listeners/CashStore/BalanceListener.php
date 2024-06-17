<?php

namespace App\Listeners\CashStore;

use App\Services\CashStore\BalanceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BalanceListener
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
        (new BalanceService($event->cashStore, $event->type))->handle();
    }
}
