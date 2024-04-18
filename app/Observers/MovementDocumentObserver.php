<?php

namespace App\Observers;

use App\Enums\DocumentHistoryStatuses;
use App\Models\ChangeHistory;
use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use App\Models\Document;
use App\Models\DocumentHistory;
use App\Models\MovementDocument;
use App\Models\Organization;
use App\Models\Storage;
use App\Models\User;
use App\Traits\TrackHistoryTrait;
use Illuminate\Support\Facades\Auth;
use PhpParser\Comment\Doc;

class MovementDocumentObserver
{
    use TrackHistoryTrait;

    public function created(MovementDocument $model): void
    {
        $this->create($model, Auth::id());
    }

    public function updated(MovementDocument $model): void
    {

        $user_id = \auth()->user()->id;
        $this->update($model, $user_id);
    }


    public function restored(MovementDocument $model): void
    {
        $user_id = \auth()->user()->id;

        $this->restore($model, $user_id);
    }

    public function forceDeleted(MovementDocument $model): void
    {
        $this->forceDelete($model, Auth::id());
    }


}
