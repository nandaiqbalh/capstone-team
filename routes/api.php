<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * API VERSION 1
 */

use App\Http\Controllers\Api\V1\Mahasiswa\Broadcast\ApiBroadcastController;
use App\Http\Controllers\Api\V1\Auth\ApiLoginController;
use App\Http\Controllers\Api\V1\Auth\ApiLogoutController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;

// profile
use App\Http\Controllers\Api\V1\Mahasiswa\Profile\ApiProfileController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login/', [ApiLoginController::class, 'authenticate']);
    Route::get('/auth/logout', [ApiLogoutController::class, 'logout']);

    Route::post('/auth/reset-password/', [ResetPasswordController::class, 'resetPasswordProcess']);
    Route::get('/mahasiswa/', [ApiLoginController::class, 'index']);

    // profile
    Route::get('/mahasiswa/profile/', [ApiProfileController::class, 'index']);
    Route::post('/mahasiswa/profile/editProcess/', [ApiProfileController::class, 'editProcess']);
    Route::post('/mahasiswa/profile/editPassword/', [ApiProfileController::class, 'editPassword']);

    Route::post('/mahasiswa/broadcast/', [ApiBroadcastController::class, 'index']);
    Route::post('/mahasiswa/broadcast/{id}', [ApiBroadcastController::class, 'detailBroadcastApi']);

});

