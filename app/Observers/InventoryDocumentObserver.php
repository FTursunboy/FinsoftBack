<?php

namespace App\Observers;

use App\Enums\DocumentHistoryStatuses;
use App\Models\ChangeHistory;
use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use App\Models\Document;
use App\Models\DocumentHistory;
use App\Models\GoodDocument;
use App\Models\InventoryDocument;
use App\Models\Organization;
use App\Models\Storage;
use App\Models\User;
use App\Traits\TrackHistoryTrait;
use Illuminate\Support\Facades\Auth;
use PhpParser\Comment\Doc;

class InventoryDocumentObserver
{
    use TrackHistoryTrait;

    public function created(InventoryDocument $model): void
    {
        $this->create($model, auth()->user()->id);
    }

    public function updated(InventoryDocument $model): void
    {
        $user_id = \auth()->user()->id ;

        $this->update($model, $user_id);
    }

    public function deleted(Document $model): void
    {
        $this->delete($model, auth()->user()->id);
    }


    public function restored(Document $model): void
    {
        $user_id = \auth()->user()->id ?? User::factory()->create()->id;

        $this->restore($model, $user_id);
    }

    public function forceDeleted(Document $model): void
    {
        $this->forceDelete($model, auth()->user()->id);
    }


}
