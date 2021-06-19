<?php

namespace App\Http\Controllers;

use App\Exceptions\IllegalStateException;
use App\Http\Requests\LogRequest;
use App\Services\LoggingService;
use Illuminate\Routing\Controller as BaseController;
use Throwable;

class LogsController extends BaseController
{
    private LoggingService $loggingService;

    public function __construct(LoggingService $loggingService)
    {
        $this->loggingService = $loggingService;
    }

    /**
     * @throws Throwable
     */
    public function saveEntrance(LogRequest $request) {
        [$device, $station, $date] = $request->validatedData();
        $this->loggingService->saveEntrance($device, $station, $date);
    }

    /**
     * @throws IllegalStateException
     * @throws Throwable
     */
    public function saveExit(LogRequest $request) {
        [$device, $station, $date] = $request->validatedData();
        $this->loggingService->saveExit($device, $station, $date);
    }
}
