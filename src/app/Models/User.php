<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = [
        'id',
        'name',
    ];
    public bool $timestamps = false;

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}
