<?php

namespace App\Repositories;

use App\DTO\GoodDTO;
use App\DTO\GoodUpdateDTO;
use App\Models\Good;
use App\Models\GoodImages;
use App\Repositories\Contracts\GoodRepositoryInterface;
use App\Traits\FilterTrait;
use App\Traits\Sort;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GoodRepository implements GoodRepositoryInterface
{
    use Sort, FilterTrait;

    public $model = Good::class;

    public function index(array $data): LengthAwarePaginator
    {
        $filterParams = $this->model::filter($data);

        $query = $this->search($filterParams['search']);

        $query = $this->filter($query, $filterParams);

        $query = $this->sort($filterParams, $query, ['unit']);

        return $query->paginate($filterParams['itemsPerPage']);
    }

    public function store(GoodDTO $DTO)
    {
        return DB::transaction(function () use ($DTO) {
            $good = Good::create([
                'name' => $DTO->name,
                'vendor_code' => $DTO->vendor_code,
                'description' => $DTO->description,
                'unit_id' => $DTO->unit_id,
                'storage_id' => $DTO->storage_id,
                'good_group_id' => $DTO->good_group_id
            ]);

            if ($DTO->add_images) GoodImages::insert($this->goodImages($good, $DTO->add_images));

        });
    }

    public function update(Good $good, GoodUpdateDTO $DTO): Good
    {
        $good->update([
            'name' => $DTO->name,
            'vendor_code' => $DTO->vendor_code,
            'description' => $DTO->description,
            'unit_id' => $DTO->unit_id,
            'storage_id' => $DTO->storage_id,
            'good_group_id' => $DTO->good_group_id
        ]);

        return $good;
    }

    public function goodImages($good, $images)
    {
        if (isset($images['main_image']))
            $img = $images['main_image'] ? Storage::disk('public')->put('goodImages', $images['main_image']) : null;

        $imgs[] = [
            'good_id' => $good->id,
            'image' => $img,
            'is_main' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        if (isset($images['add_images'])) {
            $imgs = array_merge($imgs, array_map(function ($image) use ($good) {
                $img = Storage::disk('public')->put('goodImages', $image);

                return [
                    'good_id' => $good->id,
                    'image' => $img,
                    'is_main' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }, $images['add_images']));
        }

        return $imgs;
    }

    public function search(string $search)
    {
        return $this->model::where('name', 'like', '%' . $search . '%');
    }

    public function filter($query, array $data)
    {

        return $query->when($data['category_id'], function ($query) use ($data) {
            return $query->where('category_id', $data['category_id']);
        })
            ->when($data['unit_id'], function ($query) use ($data) {
                return $query->where('unit_id', $data['unit_id']);
            })
            ->when($data['storage_id'], function ($query) use ($data) {
                return $query->where('storage_id', $data['storage_id']);
            })
            ->when($data['name'], function ($query) use ($data) {
                return $query->where('name', 'like', $data['name']);
            })
            ->when($data['vendor_code'], function ($query) use ($data) {
                return $query->where('vendor_code', 'like', $data['vendor_code']);
            })
            ->when($data['description'], function ($query) use ($data) {
                return $query->where('description', 'like', $data['description']);
            })
            ->when($data['barcode'], function ($query) use ($data) {
                return $query->where('barcode', 'like', $data['barcode']);
            });
    }
}
