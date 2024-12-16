<?php

use App\Http\Controllers\LocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('locations')
    ->middleware('throttle:60,1')
    ->group(function() {
    Route::get('/', [LocationController::class, 'index']);
    Route::post('/', [LocationController::class, 'store']);
    Route::get('/{location}', [LocationController::class, 'show']);
    Route::patch('/{location}', [LocationController::class, 'update']);
    Route::post('/route', [LocationController::class, 'route']);
});
