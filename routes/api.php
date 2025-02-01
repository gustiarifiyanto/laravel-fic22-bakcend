<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [App\Http\Controllers\Api\AuthController::class,'register']);

Route::post('/login', [App\Http\Controllers\Api\AuthController::class,'login']);

Route::post('/login/google', [App\Http\Controllers\Api\AuthController::class,'logingoogle']);

Route::post('/logout', [App\Http\Controllers\Api\AuthController::class,'logout'])->middleware('auth:sanctum');

Route::get('/events/{category_id}', [App\Http\Controllers\Api\EventController::class,'index']);

Route::get('/event-categories', [App\Http\Controllers\Api\EventController::class,'categories']);

Route::get('/event/{event_id}', [App\Http\Controllers\Api\EventController::class,'detail']);