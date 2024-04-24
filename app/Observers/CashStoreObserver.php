<?php

namespace App\Observers;

use App\Models\CashStore;
use App\Traits\TrackHistoryTrait;

class CashStoreObserver
{
    use TrackHistoryTrait;

    /**
     * Handle the CashStore "created" event.
     */
    public function created(CashStore $cashStore): void
    {
        $this->create($cashStore, \Auth::id());
    }

    /**
     * Handle the CashStore "updated" event.
     */
    public function updated(CashStore $cashStore): void
    {
        $this->update($cashStore, \Auth::id());
    }

    /**
     * Handle the CashStore "deleted" event.
     */
    public function deleted(CashStore $cashStore): void
    {
        $this->delete($cashStore, \Auth::id());
    }

    /**
     * Handle the CashStore "restored" event.
     */
    public function restored(CashStore $cashStore): void
    {
        $this->restore($cashStore, \Auth::id());
    }

    /**
     * Handle the CashStore "force deleted" event.
     */
    public function forceDeleted(CashStore $cashStore): void
    {
        //
    }
}
