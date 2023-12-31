<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\WalletManager;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/setup', function () {
    Artisan::call('storage:link');
    Artisan::call('migrate');
    Artisan::call('db:seed');
});
Route::get('/setup/m', function () {
    Artisan::call('migrate');
});
Route::get('/user/privacy_policy', function () {
    return view('privacy_policy');
});


    Route::get('/test', [WalletManager::class  ,'test']); 