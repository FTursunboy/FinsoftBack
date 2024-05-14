<?php

namespace App\Observers;

use App\Models\Document;
use App\Traits\TrackHistoryTrait;
use Illuminate\Support\Facades\Auth;

class DocumentObserver
{
    use TrackHistoryTrait;

    public function created(Document $model): void
    {
        $this->create($model, Auth::id());
    }

    public function updated(Document $model): void
    {
       $this->update($model, Auth::id());
    }

    public function deleted(Document $model): void
    {
       $this->delete($model, Auth::id());
    }

    public function restored(Document $model): void
    {
       $this->restore($model, Auth::id());
    }

    public function forceDeleted(Document $model): void
    {
        $this->forceDelete($model, Auth::id());
    }




}
