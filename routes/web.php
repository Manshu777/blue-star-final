<?php

use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\S3FileUploadController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\AuthController;






Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');

Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\ShopController;



Route::get('/', [HomeController::class, 'index'])->name('home');



// Route::view('/login', 'auth.login')->name('login');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{id}/customize', [ShopController::class, 'customize'])->name('shop.customize');
Route::post('/shop/{id}/customize', [ShopController::class, 'storeCustomization'])->name('shop.storeCustomization');



Route::get('/about', function () {
    return view('about.about');
})->name('about');

Route::get('/contact', function () {
    return view('contact.contact');
})->name('contact');

Route::get('/pricing', [PlanController::class, 'index'])->name('pricing');


Route::post('photos', [UploadController::class, 'store'])->name('photos.store');
Route::get('photos', [UploadController::class, 'index'])->name('photos.index');
Route::get('photos/{photo}', [UploadController::class, 'show'])->name('photos.show');
// Route::post('photos/{photo}', [UploadController::class, 'update'])->name('photos.update');
Route::delete('photos/{photo}', [UploadController::class, 'destroy'])->name('photos.destroy');

Route::post('/photos/analyze', [UploadController::class, 'analyzeImage']);
Route::post('/search-by-face', [UploadController::class, 'searchByFace']);


Route::post('/photos/search', [UploadController::class, 'search'])->name('photos.search');
Route::put('/photos/{id}', [UploadController::class, 'update'])->name('photos.update');
Route::delete('/photos/{id}', [UploadController::class, 'destroy'])->name('photos.destroy');
Route::post('/photos/analyze', [UploadController::class, 'analyzeImage'])->name('photos.analyze');

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
Route::get('/photos', [S3FileUploadController::class, 'index'])->name('photos.index');

//search
require __DIR__ . '/auth.php';
