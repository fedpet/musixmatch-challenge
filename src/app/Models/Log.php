<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Log extends Model
{
    public bool $timestamps = false;

    public function device(): HasOne
    {
        return $this->hasOne(Device::class);
    }

    public function fromStation(): HasOne
    {
        return $this->hasOne(Station::class, 'station_id_enter');
    }

    public function toStation(): HasOne
    {
        return $this->hasOne(Station::class, 'station_id_exit');
    }
}
