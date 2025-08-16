<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\S3FileUploadController;
use App\Http\Controllers\PhotoController;


Route::get('/', [HomeController::class, 'index'])->name('home');





Route::middleware('auth')->group(function () {
    Route::get('/photos', [PhotoController::class, 'index'])->name('photos.index');
    Route::post('/upload', [PhotoController::class, 'upload'])->name('upload');
    Route::post('/edit/sharpen', [PhotoController::class, 'sharpen'])->name('edit.sharpen');
    Route::post('/edit/color-correct', [PhotoController::class, 'colorCorrect'])->name('edit.colorCorrect');
    Route::post('/edit/remove-background', [PhotoController::class, 'removeBackground'])->name('edit.removeBackground');
    Route::post('/save', [PhotoController::class, 'save'])->name('save');
    Route::post('/share', [PhotoController::class, 'share'])->name('share');
    Route::patch('/photos/{photo}/tags', [PhotoController::class, 'updateTags'])->name('update.tags');
    Route::delete('/photos/{photo}', [PhotoController::class, 'delete'])->name('delete');
    Route::post('/toggle-merch', [PhotoController::class, 'toggleMerch'])->name('toggle.merch');
});

Route::view('/login', 'auth.login')->name('login');

Route::get('/dashboard', [ProfileController::class, 'index'])->name('user.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/store', [PurchaseController::class, 'index'])->name('store.index');
Route::get('/store/merchandise', [PurchaseController::class, 'merchandise'])->name('store.merchandise');
Route::post('/store/purchase/photo/{id}', [PurchaseController::class, 'purchasePhoto'])->name('store.purchase_photo');
Route::post('/store/purchase/merchandise', [PurchaseController::class, 'purchaseMerchandise'])->name('store.purchase_merchandise');
Route::get('/store/orders', [PurchaseController::class, 'orders'])->name('store.orders');

// Social Sharing
Route::post('/photos/share/{id}', [SocialShareController::class, 'share'])->name('photos.share');
Route::get('/photos/share/preview/{id}', [SocialShareController::class, 'previewShare'])->name('photos.share_preview');



Route::get('/upload', [S3FileUploadController::class, 'showForm'])->name('upload.form');
Route::post('/upload', [S3FileUploadController::class, 'upload'])->name('upload');



require __DIR__ . '/auth.php';
