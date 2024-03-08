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

use App\Http\Controllers\Api\V1\Mahasiswa\Mahasiswa\ApiMahasiswaController;
use App\Http\Controllers\Api\V1\Mahasiswa\Dosen\ApiDosenController;

use App\Http\Controllers\Api\V1\Mahasiswa\Kelompok\ApiKelompokSayaController;
use App\Http\Controllers\Api\V1\Mahasiswa\Siklus\ApiSiklusController;
use App\Http\Controllers\Api\V1\Mahasiswa\Topik\ApiTopikController;

use App\Http\Controllers\Api\V1\Mahasiswa\UploadFile\ApiUploadFileController;
use App\Http\Controllers\Api\V1\Mahasiswa\UploadFile\ApiUploadFileCapstoneController;

use App\Http\Controllers\Api\V1\Mahasiswa\SidangProposal\ApiSidangProposalController;
use App\Http\Controllers\Api\V1\Mahasiswa\SidangTugasAkhir\ApiSidangTugasAkhirController;

use App\Http\Controllers\Api\V1\Mahasiswa\Expo\ApiExpoController;


// profile
use App\Http\Controllers\Api\V1\Mahasiswa\Profile\ApiProfileController;



Route::prefix('v1')->group(function () {

    Route::post('/auth/login/', [ApiLoginController::class, 'authenticate']);

    // broadcast
    Route::get('/mahasiswa/broadcast/', [ApiBroadcastController::class, 'index']);
    Route::get('/mahasiswa/broadcast-home/', [ApiBroadcastController::class, 'broadcastHome']);
    Route::post('/mahasiswa/detail-broadcast/', [ApiBroadcastController::class, 'detailBroadcastApi']);


    Route::group(['middleware' => ['jwt.verify']], function () {

        // loguot
        Route::get('/auth/logout/', [ApiLogoutController::class, 'logout']);

        // profile
        Route::get('/mahasiswa/profile/', [ApiProfileController::class, 'index']);
        Route::post('/mahasiswa/profile/editProcess/', [ApiProfileController::class, 'editProcess']);
        Route::post('/mahasiswa/profile/editPassword/', [ApiProfileController::class, 'editPassword']);
        Route::post('/mahasiswa/profile/editPhotoProcess/', [ApiProfileController::class, 'editPhotoProcess']);

        // mahasiswa
        Route::get('/mahasiswa/data-mahasiswa/', [ApiMahasiswaController::class, 'index']);
        Route::get('/mahasiswa/data-dosen/', [ApiDosenController::class, 'index']);

        Route::get('/mahasiswa/kelompok/', [ApiKelompokSayaController::class, 'index']);
        Route::post('/mahasiswa/kelompok/updateStatusForward', [ApiKelompokSayaController::class, 'updateStatusKelompokForward']);
        Route::post('/mahasiswa/kelompok/updateStatusBackward', [ApiKelompokSayaController::class, 'updateStatusKelompokBackward']);
        Route::post('/mahasiswa/kelompok/add-kelompok-process', [ApiKelompokSayaController::class, 'addKelompokProcess']);
        Route::post('/mahasiswa/kelompok/add-punya-kelompok-process', [ApiKelompokSayaController::class, 'addPunyaKelompokProcess']);

        // siklus
        Route::get('/mahasiswa/siklus/', [ApiSiklusController::class, 'index']);

        // topik
        Route::get('/mahasiswa/topik/', [ApiTopikController::class, 'index']);

        Route::get('/mahasiswa/upload-file/', [ApiUploadFileController::class, 'index']);
        Route::post('/mahasiswa/upload-file/upload-makalah-process', [ApiUploadFileController::class, 'uploadMakalahProcess']);
        Route::post('/mahasiswa/upload-file/upload-laporan-process', [ApiUploadFileController::class, 'uploadLaporanProcess']);

        Route::post('/mahasiswa/upload-file/upload-c100-process', [ApiUploadFileCapstoneController::class, 'uploadC100Process']);
        Route::post('/mahasiswa/upload-file/upload-c200-process', [ApiUploadFileCapstoneController::class, 'uploadC200Process']);
        Route::post('/mahasiswa/upload-file/upload-c300-process', [ApiUploadFileCapstoneController::class, 'uploadC300Process']);
        Route::post('/mahasiswa/upload-file/upload-c400-process', [ApiUploadFileCapstoneController::class, 'uploadC400Process']);
        Route::post('/mahasiswa/upload-file/upload-c500-process', [ApiUploadFileCapstoneController::class, 'uploadC500Process']);

        Route::post('/mahasiswa/view-pdf', [ApiUploadFileController::class, 'viewPdf']);
        Route::post('/mahasiswa/profile/img-user', [ApiProfileController::class, 'imageProfile']);

        // sidang proposal
        Route::get('/mahasiswa/sidang-proposal/', [ApiSidangProposalController::class, 'index']);
        Route::get('/mahasiswa/sidang-proposal-kelompok/', [ApiSidangProposalController::class, 'sidangProposalByKelompok']);
        Route::get('/mahasiswa/sidang-proposal-download/', [ApiSidangProposalController::class, 'downloadPdf']);

        // expo
        Route::get('/mahasiswa/expo/', [ApiExpoController::class, 'index']);
        Route::post('/mahasiswa/expo-daftar/', [ApiExpoController::class, 'daftarExpo']);

        // sidang TA
        Route::post('/mahasiswa/sidang-tugas-akhir/updateStatusForward', [ApiSidangTugasAkhirController::class, 'updateStatusMahasiswaForward']);
        Route::post('/mahasiswa/sidang-tugas-akhir/updateStatusBackward', [ApiSidangTugasAkhirController::class, 'updateStatusMahasiswaBackward']);

        Route::get('/mahasiswa/sidang-tugas-akhir/', [ApiSidangTugasAkhirController::class, 'index']);
        Route::get('/mahasiswa/sidang-tugas-akhir-mahasiswa/', [ApiSidangTugasAkhirController::class, 'sidangTugasAkhirByMahasiswa']);
        Route::post('/mahasiswa/sidang-tugas-akhir-daftar/', [ApiSidangTugasAkhirController::class, 'daftarSidangTugasAkhir']);

    });
});

