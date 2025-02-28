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

    Route::get('/delight', [DelightController::class, 'index']);
    Route::post('/delight', [DelightController::class, 'store']);
    Route::get('/delight/{delight}', [DelightController::class, 'show']);
    Route::put('/delight/{delight}', [DelightController::class, 'update']);
    Route::delete('/delight/{delight}', [DelightController::class, 'destroy']);
    Route::post('delights/{delight}/eat', [DelightController::class, 'eat']);
    Route::post('delights/{delight}/spit', [DelightController::class, 'spit']);

    Route::apiResource('delights.nibbles', NibbleController::class)->shallow();

    Route::post('gourmets/{gourmet}/taste', [GourmetController::class, 'taste']);
    Route::post('gourmets/{gourmet}/spit', [GourmetController::class, 'spit']);
});
