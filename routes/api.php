<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DrinkController;
use App\Http\Controllers\MySetController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use App\Models\MasterCategory;

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

    Route::apiResource('my-sets', MySetController::class);

    Route::get('drinks/new', [DrinkController::class, 'new']);
    Route::get('drinks/select', [DrinkController::class, 'select']);
    Route::apiResource('drinks', DrinkController::class);

    Route::prefix('settings')->group(function () {
        Route::patch('plan', [SettingController::class, 'plan']);
        Route::patch('my-set', [SettingController::class, 'mySet']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/my-set', [UserController::class, 'mySet']);
        Route::get('/plan', [UserController::class, 'plan']);
    });

    Route::get('/master/category', function () {
        return response()->json([
            "success" => true,
            "categories" => MasterCategory::all()->map(function ($category) {
                return [
                    "id" => $category->id,
                    "name" => $category->name,
                ];
            }),
        ]);
    });
});
