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

use App\Http\Controllers\Api\V1\Mahasiswa\Beranda\ApiBerandaController;

use App\Http\Controllers\Api\V1\Mahasiswa\Mahasiswa\ApiMahasiswaController;
use App\Http\Controllers\Api\V1\Mahasiswa\Dosen\ApiDosenController;

use App\Http\Controllers\Api\V1\Mahasiswa\Kelompok\ApiKelompokController;
use App\Http\Controllers\Api\V1\Mahasiswa\Siklus\ApiSiklusController;
use App\Http\Controllers\Api\V1\Mahasiswa\Topik\ApiTopikController;

use App\Http\Controllers\Api\V1\Mahasiswa\Dokumen\ApiDokumenController;
use App\Http\Controllers\Api\V1\Mahasiswa\Dokumen\ApiDokumenCapstoneController;

use App\Http\Controllers\Api\V1\Mahasiswa\SidangProposal\ApiSidangProposalController;
use App\Http\Controllers\Api\V1\Mahasiswa\TugasAkhir\ApiTugasAkhirController;

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

        // beranda
        Route::get('/mahasiswa/beranda/', [ApiBerandaController::class, 'index']);

        // profile
        Route::get('/mahasiswa/profile/', [ApiProfileController::class, 'index']);
        Route::post('/mahasiswa/profile/editProcess/', [ApiProfileController::class, 'editProcess']);
        Route::post('/mahasiswa/profile/editPassword/', [ApiProfileController::class, 'editPassword']);
        Route::post('/mahasiswa/profile/editPhotoProcess/', [ApiProfileController::class, 'editPhotoProcess']);

        // mahasiswa
        Route::get('/mahasiswa/data-mahasiswa/', [ApiMahasiswaController::class, 'index']);
        Route::get('/mahasiswa/data-dosen-pembimbing1/', [ApiDosenController::class, 'dosbing1']);
        Route::get('/mahasiswa/data-dosen-pembimbing2/', [ApiDosenController::class, 'dosbing2']);

        Route::get('/mahasiswa/kelompok/', [ApiKelompokController::class, 'index']);
        Route::post('/mahasiswa/kelompok/edit-kelompok-process', [ApiKelompokController::class, 'editInformasiKelompok']);
        Route::post('/mahasiswa/kelompok/add-kelompok-process', [ApiKelompokController::class, 'addKelompokProcess']);
        Route::post('/mahasiswa/kelompok/add-punya-kelompok-process', [ApiKelompokController::class, 'addPunyaKelompokProcess']);
        Route::post('/mahasiswa/kelompok/terima-kelompok', [ApiKelompokController::class, 'terimaKelompok']);
        Route::post('/mahasiswa/kelompok/tolak-kelompok', [ApiKelompokController::class, 'tolakKelompok']);

        // siklus
        Route::get('/mahasiswa/siklus/', [ApiSiklusController::class, 'index']);

        // topik
        Route::get('/mahasiswa/topik/', [ApiTopikController::class, 'index']);

        Route::get('/mahasiswa/dokumen/', [ApiDokumenController::class, 'index']);
        Route::post('/mahasiswa/dokumen/upload-makalah-process', [ApiDokumenController::class, 'uploadMakalahProcess']);
        Route::post('/mahasiswa/dokumen/upload-laporan-process', [ApiDokumenController::class, 'uploadLaporanProcess']);

        Route::post('/mahasiswa/dokumen/upload-c100-process', [ApiDokumenCapstoneController::class, 'uploadC100Process']);
        Route::post('/mahasiswa/dokumen/upload-c200-process', [ApiDokumenCapstoneController::class, 'uploadC200Process']);
        Route::post('/mahasiswa/dokumen/upload-c300-process', [ApiDokumenCapstoneController::class, 'uploadC300Process']);
        Route::post('/mahasiswa/dokumen/upload-c400-process', [ApiDokumenCapstoneController::class, 'uploadC400Process']);
        Route::post('/mahasiswa/dokumen/upload-c500-process', [ApiDokumenCapstoneController::class, 'uploadC500Process']);

        Route::post('/mahasiswa/view-pdf', [ApiDokumenController::class, 'viewPdf']);
        Route::post('/mahasiswa/profile/img-user', [ApiProfileController::class, 'imageProfile']);

        // sidang proposal
        Route::get('/mahasiswa/sidang-proposal-kelompok/', [ApiSidangProposalController::class, 'sidangProposalByKelompok']);

        // expo
        Route::get('/mahasiswa/expo/', [ApiExpoController::class, 'index']);
        Route::post('/mahasiswa/expo-daftar/', [ApiExpoController::class, 'daftarExpo']);

        // sidang TA
        Route::post('/mahasiswa/sidang-tugas-akhir/updateStatusForward', [ApiTugasAkhirController::class, 'updateStatusMahasiswaForward']);
        Route::post('/mahasiswa/sidang-tugas-akhir/updateStatusBackward', [ApiTugasAkhirController::class, 'updateStatusMahasiswaBackward']);

        Route::get('/mahasiswa/sidang-tugas-akhir-mahasiswa/', [ApiTugasAkhirController::class, 'sidangTugasAkhirByMahasiswa']);
        Route::post('/mahasiswa/sidang-tugas-akhir-daftar/', [ApiTugasAkhirController::class, 'daftarSidangTugasAkhir']);

    });
});

