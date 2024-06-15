<?php

namespace App\Listeners\CashStore;

use App\Services\CashStore\AccountablePersonService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AccountabllePersonListener
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
        (new AccountablePersonService($event->cashStore, $event->type, $event->cashRegisterId))->handle();
    }
}
