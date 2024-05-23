<?php

namespace App\Services;

use App\Enums\MovementTypes;
use App\Models\DocumentModel;
use App\Models\GoodHistory;


class HandleGoodHistory
{
    public function __construct(public DocumentModel $document, public string $documentType) { }

    public function handle(): void
    {
        $this->history();
    }

    private function history(): void
    {
        $goods = $this->document->documentGoods;

        $insertData = [];

        foreach ($goods as $good) {

            $insertData[] = [
                'good_id' => $good->good_id,
                'document_id' => $good->document_id,
                'document_type' => get_class($this->document),
                'type' => $this->documentType,
                'created_at' => now()
            ];

        }

        GoodHistory::insert($insertData);

    }

}
