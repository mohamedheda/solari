<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\Email\ChangeEmailController;
use App\Http\Controllers\Api\V1\Auth\Otp\OtpController;
use App\Http\Controllers\Api\V1\Auth\Password\PasswordController;
use App\Http\Controllers\Api\V1\System\SystemController;
use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::group(['prefix' => 'sign'], function () {
        Route::post('in', 'signIn');
        Route::post('up', 'signUp');
        Route::post('out', 'signOut')->middleware('auth:api');
    });
    Route::get('what-is-my-platform', 'whatIsMyPlatform'); // returns 'platform: website!'
});
Route::group(['prefix' => 'otp', 'middleware' => ['auth:api']], function () {
    Route::post('/verify', [OtpController::class, 'verify']);
    Route::get('/', [OtpController::class, 'send']);
});
Route::group(['prefix' => 'email', 'middleware' => ['auth:api']], function () {
    Route::post('/change', [ChangeEmailController::class, 'sendOtp']);
    Route::post('/otp/verify', [ChangeEmailController::class, 'change']);
});
Route::group(['prefix' => 'password'], function () {
    Route::post('/forgot', [PasswordController::class, 'forgot']);
    Route::post('/verify-otp', [PasswordController::class, 'verifyOtp']);
    Route::post('/reset', [PasswordController::class, 'reset']);
    Route::post('/update', [PasswordController::class, 'updatePassword']);
});
Route::group(['prefix' => 'technician', 'middleware' => ['auth:api']], function () {
    Route::post('/', [UserController::class, 'store']);
    Route::get('/', [UserController::class, 'index']);
});
Route::group(['prefix' => 'system', 'middleware' => ['auth:api']], function () {
    Route::put('/{id}', [SystemController::class, 'update']);
    Route::post('/', [SystemController::class, 'store']);
    Route::get('/', [SystemController::class, 'index']);
    Route::get('/{id}', [SystemController::class, 'show']);
    Route::post('/cell', [SystemController::class, 'storeCell']);
    Route::get('/cell/{id}', [SystemController::class, 'getCell']);
    Route::get('/cell/{id}/faults', [SystemController::class, 'getCellFaults']);
    Route::get('/home/{id}/{cell_id}', [SystemController::class, 'getSystemData']);
});

