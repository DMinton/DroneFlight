<?php

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

Route::group(['prefix' => 'telemetry',  'namespace' => 'Telemetry'], function () {
    Route::post('save', 'TelemetryController@save');
});

Route::group(['prefix' => 'flights',  'namespace' => 'Flight'], function () {
    Route::get('/', 'FlightsController@list');
    Route::get('/{id}', 'FlightsController@details');
});
