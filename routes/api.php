<?php

use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\EpisodeController;
use App\Http\Controllers\Api\EpisodePlaybackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/albums/random', [AlbumController::class, 'random'])
    ->name('albums.random');
Route::apiResource('albums', AlbumController::class)
    ->only(['index', 'show']);

Route::get('/episodes/random', [EpisodeController::class, 'random'])
    ->name('episodes.random');
Route::apiResource('episodes', EpisodeController::class)
    ->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('episodes.played', EpisodePlaybackController::class)
        ->only(['index', 'store']);

    Route::apiResource('user/played', \App\Http\Controllers\Api\PlayedEpisodesController::class)
        ->only(['index', 'destroy']);

});
