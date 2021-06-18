<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Log;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Log::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'station_id_entrance' => self::factoryForModel(Station::class),
            'station_id_exit' => self::factoryForModel(Station::class),
            'device_id' => self::factoryForModel(Device::class),
            'dateOfEntrance' => $this->faker->dateTime
        ];
    }
}
