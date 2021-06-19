<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Device::factory()->count(5)->create();
        Station::factory()->count(20)->create();
    }
}
