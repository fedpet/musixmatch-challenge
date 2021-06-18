<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Segment extends Model
{
    public bool $timestamps = false;

    public function stationA(): HasOne
    {
        return $this->hasOne(Station::class, 'station_id_a');
    }

    public function stationB(): HasOne
    {
        return $this->hasOne(Station::class, 'station_id_b');
    }
}
