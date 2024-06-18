<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\DB;

class Coordinates implements Castable
{
    public function __construct(public float $lat, public float $lon)
    {
    }

    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return object|string
     */
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                $point = \MatanYadaev\EloquentSpatial\Objects\Point::fromWkb($value);

                return new Coordinates(
                    $point->latitude,
                    $point->longitude
                );
            }

            public function set($model, $key, $value, $attributes)
            {
                return DB::raw("ST_GeomFromText('POINT({$value->lon} {$value->lat})')");
            }
        };
    }
}

