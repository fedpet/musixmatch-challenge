<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
    ];
    public $timestamps = false;

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function logs(): HasManyThrough
    {
        return $this->hasManyThrough(Log::class, Device::class);
    }
}
