<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Log;
use App\Models\Segment;
use App\Models\Station;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Throwable;

class LogsController extends BaseController
{
    use ValidatesRequests;

    /**
     * @throws Throwable
     */
    public function saveEntrance(Request $request) {
        $deviceId = $request->input('device');
        $date = $request->input('date');
        $stationId = $request->input('station');

        $device = Device::findOrFail($deviceId);
        $station = Station::findOrFail($stationId);

        // TODO: handle case when user has never left a station, but is entering a new one

        $log = new Log([
            'dateOfEntrance' => $date
        ]);
        $log->device()->associate($device);
        $log->fromStation()->associate($station);
        $log->saveOrFail();
    }

    public function saveExit(Request $request) {
        $deviceId = $request->input('device');
        $date = $request->input('date');
        $stationId = $request->input('station');

        $device = Device::findOrFail($deviceId);
        $station = Station::findOrFail($stationId);

        // TODO: assert dateOfExit > dateOfEntrance

        $log = $device->logs()
            ->orderByDesc('dateOfEntrance')
            ->whereNull('dateOfExit')
            ->firstOrFail();

        $fromStation = $log->fromStation;
        $toStation = $station;
        $segment = Segment::between($fromStation, $toStation);
        // TODO: default price constant
        $cost = $segment == null ? 10 : $segment->cost;

        $log->dateOfExit = $date;
        $log->cost = $cost;
        $log->toStation()->associate($station);
        $log->saveOrFail();
    }
}
