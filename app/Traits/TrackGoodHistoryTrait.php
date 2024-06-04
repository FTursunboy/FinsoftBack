<?php

namespace App\Traits;

use App\Enums\ChangeGoodDocument;
use App\Models\ChangeGoodDocumentHistory;
use App\Models\ChangeHistory;
use App\Models\GoodDocument;

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
    {
        if ($type == ChangeGoodDocument::Changed->value) {
            $value = $this->getUpdated($document)
                ->mapWithKeys(function ($value, $field) use ($document) {
                    $translatedField = config('constants.' . $field);

                    return [$translatedField => $this->getHistoryDetails($document, $value, $field)];
                });
        } else {
            $value = $document;
        }

        $history =  ChangeHistory::latest('created_at')->first();
dd($value);
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
