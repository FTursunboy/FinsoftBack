<?php

namespace App\Listeners;

use App\Traits\TrackHistoryTrait;

class GoodDocumentHistoryListener
{

    use TrackHistoryTrait;
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
        $this->getUpdatedGoods($event->goods, $event->type);
    }
}
