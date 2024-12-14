<?php

use Illuminate\Support\Facades\Route;
use L5Swagger\Http\Controllers\SwaggerController;


Route::get('/docs', [SwaggerController::class, 'api']);
