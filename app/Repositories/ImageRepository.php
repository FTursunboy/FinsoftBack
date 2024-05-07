<?php

namespace App\Repositories;

use App\DTO\ImageDTO;
use App\Models\Good;
use App\Models\GoodImages;
use App\Repositories\Contracts\ImageRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\isFalse;

class ImageRepository implements ImageRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = GoodImages::class;

    public function index(Good $good, array $data)
    {
        $filterParams = $this->model::filter($data);

        return $good->images()->paginate($filterParams['itemsPerPage']);
    }

    public function store(ImageDTO $DTO)
    {
        $image = Storage::disk('public')->put('goodImages', $DTO->image);

        if ($DTO->is_main == 1 && GoodImages::where('good_id', $DTO->good_id)->exists())
            GoodImages::where('good_id', $DTO->good_id)->update(['is_main' => 0]);

        return $this->model::create([
            'good_id' => $DTO->good_id,
            'image' => $image,
            'is_main' => $DTO->is_main,
            'image_name' => $DTO->image->getClientOriginalName()
        ]);
    }

    public function delete(GoodImages $images)
    {
        $images->delete();
        Storage::delete('public/' . $images->image);
    }
}
