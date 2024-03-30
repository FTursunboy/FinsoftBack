<?php

namespace App\DTO;

use App\Http\Requests\Api\Good\GoodUpdateRequest;
use Illuminate\Http\UploadedFile;

class GoodUpdateDTO
{
    public function __construct(public string $name, public string $vendor_code, public ?string $description,
                    public int $unit_id, public int $storage_id,  public ?int $good_group_id, public ?UploadedFile $main_image, public ?array $add_images, public ?array $image_ids)
    {
    }

    public static function fromRequest(GoodUpdateRequest $request) :self
    {
        return new static(
            $request->get('name'),
            $request->get('vendor_code'),
            $request->get('description'),
            $request->get('unit_id'),
            $request->get('storage_id'),
            $request->get('good_group_id'),
            $request->file('main_image'),
            $request->allFiles('add_images'),
            $request->get('image_ids'),
        );
    }
}
