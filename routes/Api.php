<?php

use App\Http\Controllers\UploadController;
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



// Route::prefix('photos')->group(function () {
Route::get('photos', [PhotoController::class, 'index'])->name('photos.index');
Route::post('photos', [PhotoController::class, 'store'])->name('photos.store');
Route::get('photos/{photo}', [PhotoController::class, 'show'])->name('photos.show');
Route::post('photos/{photo}', [PhotoController::class, 'update'])->name('photos.update');
Route::delete('photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');
// });

Route::apiResource('users', UserController::class);
// Route::apiResource('photos', PhotoController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('albums', AlbumController::class);
Route::post('merchandise', [MerchandiseController::class, 'store']);


// Route::post('upload', [UploadController::class, 'upload']);
// Route::get('upload', [UploadController::class, 'upload']);