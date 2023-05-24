<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\AccuWeatherController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AccuWeatherController::class)->group(function(){
    Route::get('/GetCitiesList', 'FindCitiesList');
    Route::get('/GetForecastByCityName/{cityName}', 'FindForecastByCityName');
    Route::get('/GetForecastByCityKey/{cityKey}', 'FindForecastByCityKey');
    Route::post('/GetForecastByMultiCities', 'FindForecastByCityName');
});

