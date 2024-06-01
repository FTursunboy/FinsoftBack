<?php

namespace App\Listeners;

use App\Traits\TrackGoodHistoryTrait;
use App\Traits\TrackHistoryTrait;

class GoodDocumentHistoryListener
{

    use TrackGoodHistoryTrait;
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
        $this->track($event->goods, $event->type);
    }
}
