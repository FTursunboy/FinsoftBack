<?php

namespace App\Traits;

use App\Models\Document;
use Illuminate\Support\Str;

trait DocNumberTrait
{
    public function uniqueNumber(): string
    {
        $lastRecord = $this->model::query()->orderBy('doc_number', 'desc')->first();

        if (!$lastRecord) {
            $lastNumber = 1;
        } else {
            $lastNumber = (int)$lastRecord->doc_number + 1;
        }

        return str_pad($lastNumber, 7, '0', STR_PAD_LEFT);
    }

}
