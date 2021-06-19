<?php

use App\Http\Controllers\LogsController;
use App\Models\Segment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/logs/entrance', [LogsController::class, 'saveEntrance']);
Route::post('/logs/exit', [LogsController::class, 'saveExit']);
