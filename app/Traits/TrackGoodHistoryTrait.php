<?php

namespace App\Traits;

use App\Enums\DocumentHistoryStatuses;
use App\Models\BalanceArticle;
use App\Models\CashRegister;
use App\Models\ChangeGoodDocumentHistory;
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
use Illuminate\Support\Facades\Lang;

trait TrackGoodHistoryTrait
{
    private function getHistoryDetails(GoodDocument $document, $value, $field): array
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

    private function track(GoodDocument $document, string $type): void
    {dd($document);
        $value = $this->getUpdated($document)
            ->mapWithKeys(function ($value, $field) use ($document) {
                $translatedField = config('constants.' . $field);

                return [$translatedField => $this->getHistoryDetails($document, $value, $field)];
            });

        $history =  ChangeHistory::latest()->first();

        ChangeGoodDocumentHistory::create([
            'change_history_id' => $history->id,
            'body' => json_encode($value),
            'type' => $type
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
