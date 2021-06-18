<?php

namespace Database\Factories;

use App\Models\Segment;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

class SegmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Segment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'station_id_a' => self::factoryForModel(Station::class),
            'station_id_b' => self::factoryForModel(Station::class)
        ];
    }
}
