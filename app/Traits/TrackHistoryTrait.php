<?php

namespace App\Traits;

use App\Enums\DocumentHistoryStatuses;
use App\Models\BalanceArticle;
use App\Models\CashRegister;
use App\Models\ChangeHistory;
use App\Models\Counterparty;
use App\Models\CounterpartyAgreement;
use App\Models\Currency;
use App\Models\Document;
use App\Models\DocumentHistory;
use App\Models\DocumentModel;
use App\Models\Employee;
use App\Models\GoodDocument;
use App\Models\Organization;
use App\Models\OrganizationBill;
use App\Models\Storage;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;

trait TrackHistoryTrait
{
    public function create(DocumentModel $model, ?int $user_id): void
    {
        $history =  DocumentHistory::create([
            'status' => DocumentHistoryStatuses::CREATED,
            'user_id' => $user_id ?? User::factory()->create()->id,
            'document_id' => $model->id,
        ]);
        ChangeHistory::create([
            'document_history_id' => $history->id,
            'body' => json_encode([]),
        ]);
    }


    public function update(DocumentModel $model, $user_id): void
    {
        if (array_key_exists('active', $model->getDirty())) {

           $history =  DocumentHistory::create([
                'status' => $model->active === true ? DocumentHistoryStatuses::APPROVED : DocumentHistoryStatuses::UNAPPROVED,
                'user_id' => $user_id,
                'document_id' => $model->id,
            ]);

            ChangeHistory::create([
                'document_history_id' => $history->id,
                'body' => json_encode([]),
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

    public function delete(DocumentModel $model, int $user_id): void
    {
        DocumentHistory::create([
            'status' => DocumentHistoryStatuses::DELETED,
            'user_id' => $user_id,
            'document_id' => $model->id,
        ]);
    }


    public function restore(DocumentModel $model, int $user_id): void
    {
        DocumentHistory::create([
            'status' => DocumentHistoryStatuses::RESTORED,
            'user_id' => $user_id,
            'document_id' => $model->id,
        ]);
    }

    public function forceDelete(DocumentModel $model, int $user_id): void
    {
        DocumentHistory::create([
            'status' => DocumentHistoryStatuses::FORCE_DELETED,
            'user_id' => $user_id,
            'document_id' => $model->id,
        ]);
    }

    private function getHistoryDetails(DocumentModel $document, $value, $field): array
    {
        $modelMap = config('models.model_map');

        $fieldKey = isset($modelMap[$field]) ? $field . '_id' : $field;

        $previousValue = $document->getOriginal($fieldKey);

        if (isset($modelMap[$field])) {
            $model = $modelMap[$field];
            $previousModel = optional($model::find($previousValue))->name;
            $newModel = optional($model::find($value))->name;

            return [
                'previous_value' => $previousModel,
                'new_value' => $newModel,
            ];
        }

        return [
            'previous_value' => $previousValue,
            'new_value' => $value,
        ];
    }

    private function track(DocumentModel $document, DocumentHistory $history): void
    {
        $value = $this->getUpdated($document)
            ->mapWithKeys(function ($value, $field) use ($document) {
                $translatedField = config('constants.' . $field);

                return [$translatedField => $this->getHistoryDetails($document, $value, $field)];
            });


        $history =  ChangeHistory::create([
            'document_history_id' => $history->id,
            'body' => json_encode($value),
        ]);

        Cache::put('history_' . Auth::id(), $history->id);

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
