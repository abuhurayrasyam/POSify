<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/test', [HomeController::class, 'Test']);

//all routes
Route::post('/user-registration', [UserController::class, 'userRegistration'])->name('user-registration');
Route::post('/user-login', [UserController::class, 'userLogin'])->name('user-login');
Route::post('/send-otp', [UserController::class, 'sentOtp'])->name('sent-otp');
Route::post('/verify-otp', [UserController::class, 'verifyOtp'])->name('verify-otp');

Route::middleware(TokenVerificationMiddleware::class)->group(function () {
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
    Route::get('/dashboard', [UserController::class, 'dashboard']);
    Route::get('/user-logout', [UserController::class, 'userLogout']);
});