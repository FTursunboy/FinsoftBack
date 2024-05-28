<?php

namespace App\Listeners;

use App\Events\DocumentApprovedEvent;
use App\Services\HandleDocumentApproveCreated;
use App\Services\HandleGoodHistory;
use Illuminate\Contracts\Queue\ShouldQueue;

class GoodHistoryListener implements ShouldQueue
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
    public function handle(DocumentApprovedEvent $event): void
    {
        (new HandleGoodHistory($event->document, $event->documentType))->handle();
    }
}
