<?php

namespace App\Traits;

use App\Enums\ChangeGoodDocument;
use App\Enums\DocumentHistoryStatuses;
use App\Models\ChangeGoodDocumentHistory;
use App\Models\ChangeHistory;
use App\Models\DocumentHistory;
use App\Models\GoodDocument;
use Illuminate\Support\Facades\Auth;

trait TrackGoodHistoryTrait
{
    use CalculateSum;

    public array $statuses = [
        "Создан",
        "Проведен",
        "Отменено проведение"
    ];
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

        $lastHistory = DocumentHistory::where('document_id', $document->document_id)->latest()->first();

        if (in_array($lastHistory->status, $this->statuses)) {

            $documentHistory = DocumentHistory::create([
                'document_id' => $lastHistory->document_id,
                'status' => DocumentHistoryStatuses::UPDATED,
                'user_id' => Auth::id()
            ]);

            $changeHistory = ChangeHistory::create([
                'document_history_id' => $documentHistory->id,
                'body' => json_encode([]),
            ]);
            ChangeGoodDocumentHistory::create([
                'change_history_id' => $changeHistory->id,
                'good' => $document->good->name,
                'body' => json_encode($value),
                'type' => $type
            ]);
            return;
        }



        $changeHistory =  $lastHistory->changes->first();

        ChangeGoodDocumentHistory::create([
            'change_history_id' => $changeHistory->id,
            'good' => $document->good->name,
            'body' => json_encode($value),
            'type' => $type
        ]);
    }

    private function getUpdated($model)
    {
        return collect($model->getDirty())->filter(function ($value, $key) {
            return !in_array($key, ['created_at', 'updated_at', 'change_history_id', 'document_id']);
        })->mapWithKeys(function ($value, $key) {
            return [str_replace('_id', '', $key) => $value];
        });
    }

}
