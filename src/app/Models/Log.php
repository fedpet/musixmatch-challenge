<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'dateOfEntrance',
    ];

    protected $casts = [
        'dateOfEntrance' => 'datetime',
        'dateOfExit' => 'datetime'
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function fromStation(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'station_id_entrance');
    }

    public function toStation(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'station_id_exit');
    }
}
