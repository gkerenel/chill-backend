<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DelightController;
use App\Http\Controllers\NibbleController;
use App\Http\Controllers\GourmetController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/signin', [AuthController::class, 'signin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/signout', [AuthController::class, 'signout']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

    Route::apiResource('delights', DelightController::class);
    Route::apiResource('delights.nibbles', NibbleController::class)->shallow();

    Route::post('delights/{delight}/eat', [DelightController::class, 'eat']);
    Route::post('delights/{delight}/uneat', [DelightController::class, 'uneat']);

    Route::post('gourmets/{gourmet}/follow', [GourmetController::class, 'follow']);
    Route::post('gourmets/{gourmet}/unfollow', [GourmetController::class, 'unfollow']);
});
