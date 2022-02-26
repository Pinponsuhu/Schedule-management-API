<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/test', function(){
    return 'test';
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/schedules',[ScheduleController::class, 'create']);
Route::get('/schedules',[ScheduleController::class, 'all']);
Route::get('/schedules/{schedule}',[ScheduleController::class, 'show']);
Route::delete('/schedules/{schedule}',[ScheduleController::class, 'destroy']);
Route::put('/schedules/{schedule}',[ScheduleController::class, 'update']);
