<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Nnjeim\World\Models\State;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\Timezone;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/states/{countryId}', function ($countryId) {
    
    $response = State::where('country_id', $countryId)->get();
    if ($response->isNotEmpty()) {
        return response()->json($response);
    } else {
        return response()->json(['message' => 'No states found for this state'], 404);
    }
});

Route::get('/cities/{stateId}', function ($stateId) {

    $response = City::where('state_id', $stateId)->get();
    if ($response->isNotEmpty()) {
        return response()->json($response);
    } else {
        return response()->json(['message' => 'No cities found for this state'], 404);
    }
});

Route::get('/timezone/{countryId}', function ($countryId) {

    $response = Timezone::where('country_id', $countryId)->get();
    if ($response->isNotEmpty()) {
        return response()->json($response);
    } else {
        return response()->json(['message' => 'No timezones found for this country'], 404);
    }
});

// Schedule and availability API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/schedule/available-slots', [App\Http\Controllers\ScheduleController::class, 'getAvailableSlots']);
    Route::get('/schedule/teacher-appointments', [App\Http\Controllers\ScheduleController::class, 'teacherAppointments']);
    Route::get('/student/available-slots', [App\Http\Controllers\StudentController::class, 'getAvailableSlots']);
});