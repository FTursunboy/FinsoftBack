<?php

namespace App\Listeners\CashStore;

use App\Services\CashStore\InvestmentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InvestmentListener
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
        (new InvestmentService($event->cashStore, $event->type))->handle();
    }
}
