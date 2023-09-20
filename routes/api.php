<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::controller('AuthManager')->prefix('auth')->group(function () {
    Route::post('/login_or_signup', 'login_or_signup');
    Route::post('send_otp', 'SendOTP');
    Route::post('resend_otp', 'SendOTP');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::controller('UserManager')->prefix('user')->group(function () {
        Route::post('/get_current_user', 'get_current_user');
        Route::post('/update_user', 'update_user');
    });
});
