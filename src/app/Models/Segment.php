<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Segment extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function stationA(): HasOne
    {
        return $this->hasOne(Station::class, 'station_id_a');
    }

    public function stationB(): HasOne
    {
        return $this->hasOne(Station::class, 'station_id_b');
    }

    public static function between($stationA, $stationB) {
        return self::where(['station_id_a', $stationA->id, 'station_id_b', $stationB->id])
            ->orWhere(['station_id_b', $stationA->id, 'station_id_a', $stationB->id])
            ->first();
    }
}
