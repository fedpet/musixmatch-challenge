<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Log;
use App\Models\Station;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoggingTest extends TestCase
{
    use RefreshDatabase;

    public function testLoggingEntrance()
    {
        $device = Device::factory()->for(User::factory())->create();
        $station = Station::factory()->create();
        $response = $this->post('/api/logs/entrance', [
            'device' => $device->id,
            'date' => '2021-01-14 08:58:32',
            'station' => $station->id
        ]);
        $response->assertStatus(200);
        $logs = Log::all();
        $this->assertEquals(1, $logs->count());
        $log = $logs[0];
        $this->assertEquals(new Carbon('2021-01-14 08:58:32'), $log->dateOfEntrance);
        $this->assertEquals($device->id, $log->device->id);
        $this->assertEquals($station->id, $log->fromStation->id);
        $this->assertEquals(null, $log->dateOfExit);
    }

    public function testLoggingExit()
    {
        $device = Device::factory()->for(User::factory())->create();
        $fromStation = Station::factory()->create();
        $toStation = Station::factory()->create();
        Log::factory()->for($device)->createOne([
            'station_id_entrance' => $fromStation->id,
            'dateOfEntrance' => '2021-01-14 08:00'
        ]);
        $response = $this->post('/api/logs/exit', [
            'device' => $device->id,
            'date' => '2021-01-14 09:00',
            'station' => $toStation->id
        ]);
        $response->assertStatus(200);
        $logs = Log::all();
        $this->assertEquals(1, $logs->count());
        $log = $logs[0];
        $this->assertEquals(new Carbon('2021-01-14 09:00'), $log->dateOfExit);
        $this->assertEquals($toStation->id, $log->toStation->id);
        $this->assertEquals(10, $log->cost);
    }

    public function testCannotExitTwiceInARow()
    {
        $device = Device::factory()->for(User::factory())->create();
        $fromStation = Station::factory()->create();
        $toStation = Station::factory()->create();
        Log::factory()->for($device)->createOne([
            'station_id_entrance' => $fromStation->id,
            'dateOfEntrance' => '2021-01-14 08:00'
        ]);
        $response = $this->post('/api/logs/exit', [
            'device' => $device->id,
            'date' => '2021-01-14 09:00',
            'station' => $toStation->id
        ]);
        $response->assertStatus(200);
        $toStation = Station::factory()->create();
        $response = $this->post('/api/logs/exit', [
            'device' => $device->id,
            'date' => '2021-01-14 10:00',
            'station' => $toStation->id
        ]);
        $response->assertStatus(409);
    }

    public function testCannotEnterTwiceInARow()
    {
        $device = Device::factory()->for(User::factory())->create();
        $station = Station::factory()->create();
        $response = $this->post('/api/logs/entrance', [
            'device' => $device->id,
            'date' => '2021-01-14 08:58:32',
            'station' => $station->id
        ]);
        $response->assertStatus(200);
        $station = Station::factory()->create();
        $response = $this->post('/api/logs/entrance', [
            'device' => $device->id,
            'date' => '2021-01-14 08:58:32',
            'station' => $station->id
        ]);
        $response->assertStatus(409);
    }
}
