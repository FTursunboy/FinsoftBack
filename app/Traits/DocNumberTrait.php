<?php

namespace App\Traits;

use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait DocNumberTrait
{
    public function uniqueNumber(): string
    {
        DB::beginTransaction();
        try {
            $lastRecord = $this->model::query()
                ->orderBy('doc_number', 'desc')
                ->lockForUpdate()
                ->first();

            if (!$lastRecord) {
                $lastNumber = 1;
            } else {
                $lastNumber = (int)$lastRecord->doc_number + 1;
            }

            $newNumber = str_pad($lastNumber, 7, '0', STR_PAD_LEFT);

            DB::commit();

            return $newNumber;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
