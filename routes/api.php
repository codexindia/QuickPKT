<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Recharge\MobileRecharge;
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

Route::controller('PagesManager')->prefix('pages')->group(function () {
    Route::post('/privacy_policy', 'privacy_policy');
    Route::post('/terms_and_conditions', 'terms_and_conditions');
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
        Route::get('/test', 'test');
    });
    Route::controller('WalletManager')->prefix('wallet')->group(function () {
        Route::post('/get_user_balance', 'get_user_balance');
        Route::post('/get_all_transaction', 'get_all_transaction');
        Route::post('/create_fund_value', 'create_fund_value');
    });
    Route::controller('BannerManager')->prefix('banner')->group(function () {
        Route::post('/get_banners/{type}', 'get_banners');
    });
    Route::controller('AlertManager')->prefix('alert')->group(function () {
        Route::post('/get_notification', 'get_notification');
        Route::post('/mark_read', 'mark_read');
    });

    //for recharge section 
    Route::controller('OperatorManager')->prefix('operators')->group(function () {
        Route::post('/get_operators/{type}', 'get_operators');
    });

    Route::controller(MobileRecharge::class)->prefix('mobile_recharge')->group(function () {
        Route::post('/get_plans/{operator_short_code}', 'get_plans');
    });
});
