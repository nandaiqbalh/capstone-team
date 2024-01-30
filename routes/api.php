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

use App\Http\Controllers\Api\V1\Mahasiswa\Kelompok\ApiKelompokSayaController;
use App\Http\Controllers\Api\V1\Mahasiswa\UploadFile\ApiUploadFileController;
use App\Http\Controllers\Api\V1\Mahasiswa\UploadFile\ApiUploadFileCapstoneController;


// profile
use App\Http\Controllers\Api\V1\Mahasiswa\Profile\ApiProfileController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login/', [ApiLoginController::class, 'authenticate']);
    Route::post('/auth/logout', [ApiLogoutController::class, 'logout']);

    Route::post('/auth/reset-password/', [ResetPasswordController::class, 'resetPasswordProcess']);
    Route::get('/mahasiswa/', [ApiLoginController::class, 'index']);

    // profile
    Route::post('/mahasiswa/profile/', [ApiProfileController::class, 'index']);
    Route::post('/mahasiswa/profile/editProcess/', [ApiProfileController::class, 'editProcess']);
    Route::post('/mahasiswa/profile/editPassword/', [ApiProfileController::class, 'editPassword']);
    Route::post('/mahasiswa/profile/editPhotoProcess/', [ApiProfileController::class, 'editPhotoProcess']);


    Route::get('/mahasiswa/broadcast/', [ApiBroadcastController::class, 'index']);
    Route::get('/mahasiswa/broadcast-home/', [ApiBroadcastController::class, 'broadcastHome']);
    Route::post('/mahasiswa/broadcast/detail-broadcast', [ApiBroadcastController::class, 'detailBroadcastApi']);

    Route::post('/mahasiswa/kelompok/', [ApiKelompokSayaController::class, 'index']);
    Route::post('/mahasiswa/kelompok/add-kelompok-process', [ApiKelompokSayaController::class, 'addKelompokProcess']);
    Route::post('/mahasiswa/kelompok/add-punya-kelompok-process', [ApiKelompokSayaController::class, 'addPunyaKelompokProcess']);

    Route::post('/mahasiswa/upload-file/', [ApiUploadFileController::class, 'index']);
    Route::post('/mahasiswa/upload-file/upload-makalah-process', [ApiUploadFileController::class, 'uploadMakalahProcess']);
    Route::post('/mahasiswa/upload-file/upload-laporan-process', [ApiUploadFileController::class, 'uploadLaporanProcess']);

    Route::post('/mahasiswa/upload-file/upload-c100-process', [ApiUploadFileCapstoneController::class, 'uploadC100Process']);
    Route::post('/mahasiswa/upload-file/upload-c200-process', [ApiUploadFileCapstoneController::class, 'uploadC200Process']);
    Route::post('/mahasiswa/upload-file/upload-c300-process', [ApiUploadFileCapstoneController::class, 'uploadC300Process']);
    Route::post('/mahasiswa/upload-file/upload-c400-process', [ApiUploadFileCapstoneController::class, 'uploadC400Process']);
    Route::post('/mahasiswa/upload-file/upload-c500-process', [ApiUploadFileCapstoneController::class, 'uploadC500Process']);

    Route::post('/mahasiswa/view-pdf', [ApiUploadFileController::class, 'viewPdf']);
    Route::post('/mahasiswa/profile/img-user', [ApiProfileController::class, 'imageProfile']);

});

