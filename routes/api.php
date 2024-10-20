<?php

use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\EpisodeController;
use App\Http\Controllers\PlayedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/episodes/random', [EpisodeController::class, 'random'])
        ->name('episodes.random');
    Route::apiResource('episodes', EpisodeController::class)
        ->only(['index', 'show']);

    Route::get('/albums/random', [AlbumController::class, 'random'])
        ->name('albums.random');
    Route::apiResource('albums', AlbumController::class)
        ->only(['index', 'show']);

    Route::apiResource('episodes.played', PlayedController::class)
        ->only(['index', 'store']);

});
