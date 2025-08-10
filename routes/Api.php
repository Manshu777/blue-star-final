<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PhotoController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AlbumController;
use App\Http\Controllers\API\MerchandiseController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('users', UserController::class);
Route::apiResource('photos', PhotoController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('albums', AlbumController::class);
Route::post('merchandise', [MerchandiseController::class, 'store']);