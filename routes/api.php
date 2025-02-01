<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DrinkController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/sign-up', [AuthController::class, 'signUp']);
});

Route::middleware("auth:sanctum")->group(function () {
    Route::get('/token', function (Request $request) {
        return response()->json([
            "success" => true,
            "authToken" => $request->user()->createToken("token")->plainTextToken,
        ]);
    });

    Route::apiResource('drinks', DrinkController::class);
});
