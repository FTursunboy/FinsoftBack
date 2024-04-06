<?php

namespace App\Observers;

use App\Enums\DocumentHistoryStatuses;
use App\Models\ChangeHistory;
use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use App\Models\Document;
use App\Models\DocumentHistory;
use App\Models\Organization;
use App\Models\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PhpParser\Comment\Doc;

class DocumentObserver
{
    public function created(Document $model): void
    {

        $user_id = \auth()->user()->id ?? User::factory()->create()->id;
        DocumentHistory::create([
            'status' => DocumentHistoryStatuses::CREATED,
            'user_id' => $user_id,
            'document_id' => $model->id,
        ]);
    }


    public function updated(Document $model): void
    {
        $user_id = \auth()->user()->id ?? User::factory()->create()->id;

        if (array_key_exists('active', $model->getDirty())) {

             DocumentHistory::create([
                'status' => $model->active === true ? DocumentHistoryStatuses::APPROVED : DocumentHistoryStatuses::UNAPPROVED,
                'user_id' => $user_id,
                'document_id' => $model->id,
            ]);

        } else {
            $documentHistory = DocumentHistory::create([
                'status' => DocumentHistoryStatuses::UPDATED,
                'user_id' => $user_id,
                'document_id' => $model->id,
            ]);
            $this->track($model, $documentHistory);
        }
    }

    public function deleted(Document $model): void
    {
        $user_id = \auth()->user()->id ?? User::factory()->create()->id;

        DocumentHistory::create([
            'status' => DocumentHistoryStatuses::DELETED,
            'user_id' => $user_id,
            'document_id' => $model->id,
        ]);
    }


    public function restored(Document $model): void
    {
        $user_id = \auth()->user()->id ?? User::factory()->create()->id;

        DocumentHistory::create([
            'status' => DocumentHistoryStatuses::RESTORED,
            'user_id' => $user_id,
            'document_id' => $model->id,
        ]);
    }

    public function forceDeleted(Document $model): void
    {
        DocumentHistory::create([
            'status' => DocumentHistoryStatuses::FORCE_DELETED,
            'user_id' => Auth::user()->id,
            'document_id' => $model->id,
        ]);
    }

    private function getHistoryDetails(Document $document, $value, $field): array
    {
        $previousValue = $field !== 'date' ? $document->getOriginal($field . '_id') : $document->getOriginal($field);

        $modelMap = [
            'storage' => Storage::class,
            'counterparty' => Counterparty::class,
            'counterparty_agreement' => CounterpartyAgreement::class,
            'organization' => Organization::class,
        ];

        if (!isset($modelMap[$field])) {
            return [
                'previous_value' => $previousValue,
                'new_value' => $value,
            ];
        }

        $model = $modelMap[$field];
        $previousModel = $model::find($previousValue)->name;
        $newModel = $model::find($value)->name;

        return [
            'previous_value' => $previousModel,
            'new_value' => $newModel,
        ];
    }

    private function track(Document $document, DocumentHistory $history): void
    {
        $value = $this->getUpdated($document)
            ->map(function ($value, $field) use ($document) {
               return $this->getHistoryDetails($document, $value, $field);
            });

        ChangeHistory::create([
            'document_history_id' => $history->id,
            'body' => json_encode($value)
        ]);
    }

    private function getUpdated($model)
    {
        return collect($model->getDirty())->filter(function ($value, $key) {
            return !in_array($key, ['created_at', 'updated_at']);
        })->mapWithKeys(function ($value, $key) {
            return [str_replace('_id', '', $key) => $value];
        });
    }

}
