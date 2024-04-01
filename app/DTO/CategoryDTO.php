<?php

namespace App\DTO;

use App\Http\Requests\Api\Category\CategoryRequest;
use Illuminate\Http\Request;

class CategoryDTO
{
    public function __construct(public string $name)
    {
    }

    public static function fromRequest(CategoryRequest $request) :self
    {
        return new static(
            $request->get('name'),
        );
    }
}
