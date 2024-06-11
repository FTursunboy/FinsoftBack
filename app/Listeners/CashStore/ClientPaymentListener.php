<?php

namespace App\Listeners\CashStore;

use App\Events\CashStore\ClientPaymentEvent;
use App\Services\CashStore\ClientPaymentApproveCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClientPaymentListener
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
    public function handle(ClientPaymentEvent $event): void
    {
        (new ClientPaymentApproveCreated($event->cashStore))->handle();
    }
}
