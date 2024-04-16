<?php

namespace App\Observers;

use App\Models\Document;
use App\Traits\TrackHistoryTrait;

class DocumentObserver
{
    use TrackHistoryTrait;

    public function created(Document $model): void
    {
        $this->create($model);
    }


    public function updated(Document $model): void
    {
       $this->update($model);
    }

    public function deleted(Document $model): void
    {
       $this->delete($model);
    }


    public function restored(Document $model): void
    {
       $this->restore($model);
    }

    public function forceDeleted(Document $model): void
    {
        $this->forceDelete($model);
    }




}
