<?php

namespace App\Observers;

use App\Models\CheckingAccount;
use App\Traits\TrackHistoryTrait;

class CheckingAccountObserver
{
    use TrackHistoryTrait;
    /**
     * Handle the CheckingAccount "created" event.
     */
    public function created(CheckingAccount $checkingAccount): void
    {
        $this->create($checkingAccount, \Auth::id());
    }

    /**
     * Handle the CheckingAccount "updated" event.
     */
    public function updated(CheckingAccount $checkingAccount): void
    {
        $this->update($checkingAccount, \Auth::id());
    }

    /**
     * Handle the CheckingAccount "deleted" event.
     */
    public function deleted(CheckingAccount $checkingAccount): void
    {
        $this->delete($checkingAccount, \Auth::id());
    }

    /**
     * Handle the CheckingAccount "restored" event.
     */
    public function restored(CheckingAccount $checkingAccount): void
    {
        $this->restore($checkingAccount, \Auth::id());
    }

    /**
     * Handle the CheckingAccount "force deleted" event.
     */
    public function forceDeleted(CheckingAccount $checkingAccount): void
    {
        //
    }
}
