<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogRequest;
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
    public function saveEntrance(LogRequest $request) {
        [$device, $station, $date] = $request->validatedData();
        // TODO: handle case when user has never left a station, but is entering a new one

        $log = new Log([
            'dateOfEntrance' => $date
        ]);
        $log->device()->associate($device);
        $log->fromStation()->associate($station);
        $log->saveOrFail();
    }

    public function saveExit(LogRequest $request) {
        [$device, $station, $date] = $request->validatedData();

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
