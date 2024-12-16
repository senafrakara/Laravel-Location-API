<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    const EARTH_RADIUS = 6371;
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'color'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    public function calculateDistanceLocations(Location $location): float|int
    {
        if (is_null($this->latitude) || is_null($this->longitude) ||
            is_null($location->latitude) || is_null($location->longitude)) {
            throw new \Exception('Coordinates are missing for distance calculation.');
        }

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($location->latitude);
        $lonTo = deg2rad($location->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(
                sqrt(
                    pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
                )
            );

        return $angle * self::EARTH_RADIUS;
    }
}
