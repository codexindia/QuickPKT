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

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});
Route::controller('AuthManager')->middleware('throttle:api')->prefix('auth')->group(function () {
    Route::post('/login_or_signup', 'login_or_signup');
    Route::post('send_otp', 'SendOTP');
    Route::post('resend_otp', 'SendOTP');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::controller('UserManager')->prefix('user')->group(function () {
        Route::post('/get_current_user', 'get_current_user');
        Route::post('/update_user', 'update_user'); 
    });
    Route::controller('WalletManager')->prefix('wallet')->group(function () {
        Route::post('/get_user_balance', 'get_user_balance');
        Route::post('/get_all_transaction', 'get_all_transaction'); 
        Route::post('/create_fund_value', 'create_fund_value'); 
    });
});
