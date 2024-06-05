<?php

namespace App\Observers;

use App\Enums\ChangeGoodDocument;
use App\Models\GoodDocument;
use App\Traits\TrackGoodHistoryTrait;
use Illuminate\Support\Facades\Auth;

class GoodDocumentObserver
{
    use TrackGoodHistoryTrait;

    public function created(GoodDocument $goodDocument): void
    {
        $this->track($goodDocument, ChangeGoodDocument::Created->value);
    }


    public function updated(GoodDocument $goodDocument): void
    {
        $this->track($goodDocument, ChangeGoodDocument::Changed->value);
    }

    public function deleted(GoodDocument $goodDocument): void
    {
        //
    }

    public function restored(GoodDocument $goodDocument): void
    {
        //
    }

    public function forceDeleted(GoodDocument $goodDocument): void
    {
        //
    }
}
