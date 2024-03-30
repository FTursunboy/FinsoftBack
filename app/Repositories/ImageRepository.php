<?php

namespace App\Repositories;

use App\DTO\ImageDTO;
use App\Models\Good;
use App\Models\GoodImages;
use App\Repositories\Contracts\ImageRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\isFalse;

class ImageRepository implements ImageRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = GoodImages::class;

    public function store(ImageDTO $DTO)
    {
        $image = Storage::disk('public')->put('goodImages', $DTO->image);

        return $this->model::create([
            'good_id' => $DTO->good_id,
            'image' => $image,
            'is_main' => $DTO->is_main
        ]);
    }

    public function update(GoodImages $image, ImageDTO $DTO)
    {
        return $image->update([
            'good_id' => $DTO->good_id,
            'image' => $image,
            'is_main' => $DTO->is_main
        ]);
    }

    public function delete(GoodImages $images)
    {
        //
    }

    public function index(Good $good, array $data): LengthAwarePaginator
    {
        $filterParams = $this->processSearchData($data);

        $query = $this->search($filterParams['search']);

        $query = $query->where('good_id', $good->id);

        $query = $this->sort($filterParams, $query, []);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function search(string $search)
    {
        $searchTerm = explode(' ', $search);

        return $this->model::where('barcode', 'like', '%' . implode('%', $searchTerm) . '%');
    }
}
