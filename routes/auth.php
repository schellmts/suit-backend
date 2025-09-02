<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

// Route::get('auth', function() {
//     if(Auth::guard('api')->check()){
//         return response()->json(['message' => 'Authenticated.']);
//     }else{
//         // Cookie::queue(Cookie::forget('web'));
//         return response()->json(['message' => 'Unauthenticated.'], 401);
//     }
// });

// Route::middleware('guest')->group(function () {
//     Route::post('register', [RegisteredUserController::class, 'store']);

//     Route::post('login', [AuthenticatedSessionController::class, 'store']);

//     Route::post('forgot-password-link', [PasswordResetLinkController::class, 'store']);
//     Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
//         ->name('password.reset');

//     Route::post('reset-password', [NewPasswordController::class, 'store'])
//         ->name('password.store');

//     Route::post('verification-email-notification', [EmailVerificationNotificationController::class, 'store'])
//         ->middleware('throttle:6,1')
//         ->name('verification.send');

//     Route::post('verify-email/{id}/{hash}', VerifyEmailController::class)
//         ->name('verification.verify');
// });

// Route::middleware('auth')->group(function () {
//     Route::put('password', [PasswordController::class, 'update'])->name('password.update');

//     Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
//         ->name('logout');
// });
