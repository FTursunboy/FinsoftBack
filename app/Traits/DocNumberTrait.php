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
            DB::commit();


            return $lastNumber;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
