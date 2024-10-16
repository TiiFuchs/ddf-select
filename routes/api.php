<?php

use App\Http\Controllers\EpisodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/episodes/random', [EpisodeController::class, 'random'])
        ->name('episodes.random');
    Route::apiResource('/episodes', EpisodeController::class)
        ->only('index', 'show');

});
