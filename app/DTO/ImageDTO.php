<?php

namespace App\DTO;

use App\Http\Requests\Api\BarcodeRequest;
use App\Http\Requests\Api\Image\ImageRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ImageDTO
{
    public function __construct(public int $good_id, public UploadedFile $image, public bool $is_main) { }

    public static function fromRequest(ImageRequest $request) :self
    {
        return new static(
            $request->get('good_id'),
            $request->file('image'),
            $request->get('is_main'),
        );
    }
}
