<?php

namespace App\Listeners;

use App\Events\SmallRemainderEvent;
use App\Jobs\SmallRemainderJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SmallRemainderListener
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
    public function handle(SmallRemainderEvent $event): void
    {
        SmallRemainderJob::dispatch($event->good_id);
    }
}
