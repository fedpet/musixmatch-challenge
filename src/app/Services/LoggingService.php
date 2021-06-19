<?php


namespace App\Services;


use App\Exceptions\IllegalStateException;
use App\Models\Device;
use App\Models\Log;
use App\Models\Segment;
use App\Models\Station;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Throwable;

class LoggingService
{
    const DEFAULT_PRICE = 10;
    /**
     * @throws IllegalStateException
     * @throws Throwable
     */
    public function saveEntrance(Device $device, Station $station, Carbon $date) {
        $log = $device->currentLog();
        if($log != null){
            throw new IllegalStateException();
        }

        $log = new Log([
            'dateOfEntrance' => $date
        ]);
        $log->device()->associate($device);
        $log->fromStation()->associate($station);
        $log->saveOrFail();
    }

    /**
     * @throws IllegalStateException
     * @throws Throwable
     */
    public function saveExit(Device $device, Station $toStation, Carbon $dateOfExit) {
        $log = $device->currentLog();
        if($log == null){
            throw new IllegalStateException();
        }
        if($log->dateOfEntrance->greaterThan($dateOfExit)) {
            throw new BadRequestException();
        }

        $segment = Segment::between($log->fromStation, $toStation);
        $cost = $segment == null ? self::DEFAULT_PRICE : $segment->cost;

        $log->dateOfExit = $dateOfExit;
        $log->cost = $cost;
        $log->toStation()->associate($toStation);
        $log->saveOrFail();
    }
}
