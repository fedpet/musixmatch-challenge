<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogRequest;
use App\Models\Log;
use App\Models\Segment;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;
use Throwable;

class LogsController extends BaseController
{
    use ValidatesRequests;

    const DEFAULT_PRICE = 10;

    /**
     * @throws Throwable
     */
    public function saveEntrance(LogRequest $request) {
        [$device, $station, $date] = $request->validatedData();

        $log = $device->currentLog();
        if($log != null){
            return response(null, 409);
        }

        $log = new Log([
            'dateOfEntrance' => $date
        ]);
        $log->device()->associate($device);
        $log->fromStation()->associate($station);
        $log->saveOrFail();
    }

    /**
     * @throws ValidationException
     */
    public function saveExit(LogRequest $request) {
        [$device, $toStation, $dateOfExit] = $request->validatedData();

        $log = $device->currentLog();
        if($log == null){
            return response(null, 409);
        }
        if($log->dateOfEntrance->greaterThan($dateOfExit)) {
            return response(null, 400);
        }

        $segment = Segment::between($log->fromStation, $toStation);
        $cost = $segment == null ? self::DEFAULT_PRICE : $segment->cost;

        $log->dateOfExit = $dateOfExit;
        $log->cost = $cost;
        $log->toStation()->associate($toStation);
        $log->saveOrFail();
    }
}
