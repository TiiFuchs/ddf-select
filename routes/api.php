<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('episodes/random', [\App\Http\Controllers\EpisodeController::class, 'random'])
    ->name('episodes.random');

//Route::resource('/episodes', \App\Http\Controllers\EpisodeController::class);
