<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * USE
 */

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\TimCapstone\DashboardController;


use App\Http\Controllers\Superadmin\Settings\RoleController;
use App\Http\Controllers\Superadmin\Settings\MenuController;
use App\Http\Controllers\Superadmin\Settings\AccountController;
use App\Http\Controllers\Superadmin\Settings\AccountsController;
use App\Http\Controllers\Superadmin\Settings\SmtpController;
use App\Http\Controllers\Superadmin\Settings\RestApiController;
use App\Http\Controllers\Superadmin\Settings\ContohHalamanController;
use App\Http\Controllers\Superadmin\Settings\LogsController;
use App\Http\Controllers\Superadmin\Settings\TakeOverLoginController;

use App\Http\Controllers\User\Home\HomeController;
use App\Http\Controllers\TimCapstone\Mahasiswa\MahasiswaController;
use App\Http\Controllers\TimCapstone\Topik\TimCapstoneController;
use App\Http\Controllers\TimCapstone\Topik\TopikController;
use App\Http\Controllers\TimCapstone\RuangSidang\RuangSidangController;
use App\Http\Controllers\TimCapstone\Dosen\DosenController;
use App\Http\Controllers\TimCapstone\Siklus\SiklusController;
use App\Http\Controllers\TimCapstone\Broadcast\BroadcastController;
use App\Http\Controllers\TimCapstone\JadwalPendaftaranKelompok\JadwalPendaftaranKelompokController;
use App\Http\Controllers\TimCapstone\JadwalSidangProposal\JadwalSidangProposalController;
use App\Http\Controllers\TimCapstone\JadwalExpo\JadwalExpoController;
use App\Http\Controllers\TimCapstone\Kelompok\KelompokController;
use App\Http\Controllers\TimCapstone\Pendaftaran\PendaftaranController;

// mahasiswa
use App\Http\Controllers\Mahasiswa\Kelompok_Mahasiswa\MahasiswaKelompokController;
use App\Http\Controllers\Mahasiswa\Expo_Mahasiswa\MahasiswaExpoController;
use App\Http\Controllers\Mahasiswa\SidangProposal_Mahasiswa\MahasiswaSidangProposalController;

use App\Http\Controllers\TimCapstone\UploadFile\UploadFileController;

// use App\Http\Controllers\Mahasiswa\Kelompok\MahasiswaKelompokController;
use App\Http\Controllers\Dosen\Bimbingan_Saya\BimbinganSayaController;
use App\Http\Controllers\Dosen\Pengujian\PengujianController;

// api
use App\Http\Controllers\Api\V1\Mahasiswa\UploadFile\ApiUploadFileController;



/**
 * PUBLIC
 */
Route::get('', [LoginController::class, 'index'])->name('/login')->middleware('guest');

/**
 * AUTH
 */
Route::get('/login', [LoginController::class, 'index'])->name('/login')->middleware('guest');
Route::post('/login/process', [LoginController::class, 'authenticate']);
Route::get('/lupa-password', [ResetPasswordController::class, 'index']);
Route::post('/lupa-password/process', [ResetPasswordController::class, 'resetPasswordProcess']);
Route::get('/ubah-password', [ResetPasswordController::class, 'ubahPassword']);
Route::post('/ubah-password/process', [ResetPasswordController::class, 'ubahPasswordProcess']);

 // superadmin
 Route::middleware(['auth', 'role:01'])->group(function () {

    Route::get('/admin/settings/role', [RoleController::class, 'index']);
    Route::get('/admin/settings/role/add', [RoleController::class, 'add']);
    Route::post('/admin/settings/role/add_process', [RoleController::class, 'addProcess']);
    Route::get('/admin/settings/role/edit/{id}', [RoleController::class, 'edit']);
    Route::post('/admin/settings/role/edit_process', [RoleController::class, 'editProcess']);
    Route::get('/admin/settings/role/delete_process/{id}', [RoleController::class, 'deleteProcess']);
    Route::get('/admin/settings/role/search', [RoleController::class, 'search']);

    // menu
    Route::get('/admin/settings/menu', [MenuController::class, 'index']);
    Route::get('/admin/settings/menu/add', [MenuController::class, 'add']);
    Route::post('/admin/settings/menu/add_process', [MenuController::class, 'addProcess']);
    Route::get('/admin/settings/menu/edit/{id}', [MenuController::class, 'edit']);
    Route::post('/admin/settings/menu/edit_process', [MenuController::class, 'editProcess']);
    Route::get('/admin/settings/menu/delete_process/{id}', [MenuController::class, 'deleteProcess']);
    Route::get('/admin/settings/menu/search', [MenuController::class, 'search']);
    Route::get('/admin/settings/menu/role_menu/{id}', [MenuController::class, 'roleMenu']);
    Route::post('/admin/settings/menu/role_menu_process', [MenuController::class, 'roleMenuProcess']);

    // akun user
    Route::get('/admin/settings/accounts', [AccountsController::class, 'index']);
    Route::get('/admin/settings/accounts/add', [AccountsController::class, 'add']);
    Route::post('/admin/settings/accounts/add_process', [AccountsController::class, 'addProcess']);
    Route::get('/admin/settings/accounts/edit/{id}', [AccountsController::class, 'edit']);
    Route::post('/admin/settings/accounts/edit_process', [AccountsController::class, 'editProcess']);
    Route::get('/admin/settings/accounts/edit_password/{id}', [AccountsController::class, 'editPassword']);
    Route::post('/admin/settings/accounts/edit_password_process', [AccountsController::class, 'editPasswordProcess']);
    Route::get('/admin/settings/accounts/delete_process/{id}', [AccountsController::class, 'deleteProcess']);
    Route::get('/admin/settings/accounts/search', [AccountsController::class, 'search']);

     // take over login
    Route::get('admin/settings/take-over-login', [TakeOverLoginController::class, 'takeOverProcess']);

});

// tim capstone
 Route::middleware(['auth', 'role:02'])->group(function () {

     //mahasiswa
     Route::get('/admin/mahasiswa', [MahasiswaController::class, 'index']);
     Route::get('/admin/mahasiswa/add', [MahasiswaController::class, 'addMahasiswa']);
     Route::post('/admin/mahasiswa/add-process', [MahasiswaController::class, 'addMahasiswaProcess']);
     Route::get('/admin/mahasiswa/delete-process/{user_id}', [MahasiswaController::class, 'deleteMahasiswaProcess']);
     Route::get('/admin/mahasiswa/edit/{user_id}', [MahasiswaController::class, 'editMahasiswa']);
     Route::post('/admin/mahasiswa/edit-process', [MahasiswaController::class, 'editMahasiswaProcess']);
     Route::get('/admin/mahasiswa/detail/{user_id}', [MahasiswaController::class, 'detailMahasiswa']);
     Route::get('/admin/mahasiswa/search', [MahasiswaController::class, 'searchMahasiswa']);

     //topik
     Route::get('/admin/topik', [TopikController::class, 'index']);
     Route::get('/admin/topik/add', [TopikController::class, 'addTopik']);
     Route::post('/admin/topik/add-process', [TopikController::class, 'addTopikProcess']);
     Route::get('/admin/topik/delete-process/{id}', [TopikController::class, 'deleteTopikProcess']);
     Route::get('/admin/topik/edit/{id}', [TopikController::class, 'editTopik']);
     Route::post('/admin/topik/edit-process', [TopikController::class, 'editTopikProcess']);

     //ruang sidang
     Route::get('/admin/ruangan', [RuangSidangController::class, 'index']);
     Route::get('/admin/ruangan/add', [RuangSidangController::class, 'create']);
     Route::post('/admin/ruangan/add-process', [RuangSidangController::class, 'store']);
     Route::get('/admin/ruangan/delete-process/{id}', [RuangSidangController::class, 'delete']);
     Route::get('/admin/ruangan/edit/{id}', [RuangSidangController::class, 'edit']);
     Route::post('/admin/ruangan/edit-process', [RuangSidangController::class, 'update']);

     //dosen
     Route::get('/admin/dosen/dosen-to-aktif-1/{id}', [DosenController::class, 'toAktifKetersediaan1'])->name('to.aktif.ketersediaan.1');
     Route::get('/admin/dosen/dosen-to-inaktif-1/{id}', [DosenController::class, 'toInaktifKetersediaan1'])->name('to.inaktif.ketersediaan.1');
     Route::get('/admin/dosen/dosen-to-aktif-2/{id}', [DosenController::class, 'toAktifKetersediaan2'])->name('to.aktif.ketersediaan.2');
     Route::get('/admin/dosen/dosen-to-inaktif-2/{id}', [DosenController::class, 'toInaktifKetersediaan2'])->name('to.inaktif.ketersediaan.2');
     Route::get('/admin/dosen', [DosenController::class, 'index']);
     Route::get('/admin/dosen/add', [DosenController::class, 'addDosen']);
     Route::post('/admin/dosen/add-process', [DosenController::class, 'addDosenProcess']);
     Route::get('/admin/dosen/delete-process/{user_id}', [DosenController::class, 'deleteDosenProcess']);
     Route::get('/admin/dosen/edit/{user_id}', [DosenController::class, 'editDosen']);
     Route::post('/admin/dosen/edit-process', [DosenController::class, 'editDosenProcess']);
     Route::get('/admin/dosen/detail/{user_id}', [DosenController::class, 'detailDosen']);
     Route::get('/admin/dosen/search', [DosenController::class, 'searchDosen']);


     //siklus
     Route::get('/admin/siklus', [SiklusController::class, 'index']);
     Route::get('/admin/siklus/add', [SiklusController::class, 'addSiklus']);
     Route::post('/admin/siklus/add-process', [SiklusController::class, 'addSiklusProcess']);
     Route::get('/admin/siklus/delete-process/{id}', [SiklusController::class, 'deleteSiklusProcess']);
     Route::get('/admin/siklus/edit/{id}', [SiklusController::class, 'editSiklus']);
     Route::post('/admin/siklus/edit-process', [SiklusController::class, 'editSiklusProcess']);
     Route::get('/admin/siklus/detail/{id}', [SiklusController::class, 'detailSiklus']);

     //broadcast
     Route::get('/admin/broadcast', [BroadcastController::class, 'index']);
     Route::get('/admin/broadcast/add', [BroadcastController::class, 'addBroadcast']);
     Route::post('/admin/broadcast/add-process', [BroadcastController::class, 'addBroadcastProcess']);
     Route::get('/admin/broadcast/delete-process/{id}', [BroadcastController::class, 'deleteBroadcastProcess']);
     Route::get('/admin/broadcast/edit/{user_id}', [BroadcastController::class, 'editBroadcast']);
     Route::post('/admin/broadcast/edit-process', [BroadcastController::class, 'editBroadcastProcess']);
     Route::get('/admin/broadcast/detail/{user_id}', [BroadcastController::class, 'detailBroadcast']);

     //kelompok
     Route::get('/admin/jadwal-pendaftaran/kelompok', [JadwalPendaftaranKelompokController::class, 'index']);
     Route::post('/admin/jadwal-pendaftaran/kelompok/add-process', [JadwalPendaftaranKelompokController::class, 'addJadwalPendaftaranKelompokProcess']);
     Route::get('/admin/jadwal-pendaftaran/kelompok/delete-process/{id}', [JadwalPendaftaranKelompokController::class, 'deleteJadwalPendaftaranKelompokProcess']);
     Route::post('/admin/jadwal-pendaftaran/kelompok/edit-process', [JadwalPendaftaranKelompokController::class, 'editJadwalPendaftaranKelompokProcess']);

     //sidang proposal
     Route::get('/admin/jadwal-pendaftaran/sidang-proposal', [JadwalSidangProposalController::class, 'index']);
     Route::get('/admin/jadwal-pendaftaran/sidang-proposal/add', [JadwalSidangProposalController::class, 'addJadwalSidangProposal']);
     Route::post('/admin/jadwal-pendaftaran/sidang-proposal/add-process', [JadwalSidangProposalController::class, 'addJadwalSidangProposalProcess']);
     Route::get('/admin/jadwal-pendaftaran/sidang-proposal/delete-process/{id}', [JadwalSidangProposalController::class, 'deleteJadwalSidangProposalProcess']);
     Route::get('/admin/jadwal-pendaftaran/sidang-proposal/edit/{user_id}', [JadwalSidangProposalController::class, 'editJadwalSidangProposal']);
     Route::post('/admin/jadwal-pendaftaran/sidang-proposal/edit-process', [JadwalSidangProposalController::class, 'editJadwalSidangProposalProcess']);
     // Route::get('/admin/jadwal-pendaftaran/sidang-proposal/detail/{user_id}', [JadwalSidangProposalController::class, 'detailBroadcast']);

     //expo
     Route::get('/admin/jadwal-pendaftaran/expo', [JadwalExpoController::class, 'index']);
     Route::post('/admin/jadwal-pendaftaran/expo/add-process', [JadwalExpoController::class, 'addJadwalExpoProcess']);
     Route::get('/admin/jadwal-pendaftaran/expo/delete-process/{id}', [JadwalExpoController::class, 'deleteJadwalExpoProcess']);
     Route::post('/admin/jadwal-pendaftaran/expo/edit-process', [JadwalExpoController::class, 'editJadwalExpoProcess']);
     Route::get('/admin/jadwal-pendaftaran/expo/detail/{user_id}', [JadwalExpoController::class, 'detailJadwalExpo']);

     Route::get('/admin/jadwal-pendaftaran/expo/terima/{id}', [JadwalExpoController::class, 'terimaKelompok']);
     Route::get('/admin/jadwal-pendaftaran/expo/tolak/{id}', [JadwalExpoController::class, 'tolakKelompok']);

     //sidangta
     Route::get('/admin/jadwal-pendaftaran/sidangta', [JadwalSidangTAController::class, 'index']);
     Route::post('/admin/jadwal-pendaftaran/sidangta/add-process', [JadwalSidangTAController::class, 'addJadwalExpoProcess']);
     Route::get('/admin/jadwal-pendaftaran/sidangta/delete-process/{id}', [JadwalSidangTAController::class, 'deleteJadwalExpoProcess']);
     Route::post('/admin/jadwal-pendaftaran/sidangta/edit-process', [JadwalSidangTAController::class, 'editJadwalExpoProcess']);
     Route::get('/admin/jadwal-pendaftaran/sidangta/detail/{user_id}', [JadwalSidangTAController::class, 'detailJadwalExpo']);

     Route::get('/admin/jadwal-pendaftaran/sidangta/terima/{id}', [JadwalSidangTAController::class, 'terimaKelompok']);
     Route::get('/admin/jadwal-pendaftaran/sidangta/tolak/{id}', [JadwalSidangTAController::class, 'tolakKelompok']);


     //kelompok
     Route::get('/admin/kelompok', [KelompokController::class, 'index']);
     Route::get('/admin/kelompok/search', [KelompokController::class, 'search']);
     Route::get('/admin/kelompok/add-mahasiswa-kelompok', [KelompokController::class, 'addMahasiswaKelompok']);
     Route::get('/admin/kelompok/add-dosen-kelompok', [KelompokController::class, 'addDosenKelompok']);
     Route::get('/admin/kelompok/delete-process/{id}', [KelompokController::class, 'deleteKelompokProcess']);
     Route::get('/admin/kelompok/edit/{id}', [KelompokController::class, 'editSiklus']);
     Route::post('/admin/kelompok/edit-kelompok-process', [KelompokController::class, 'editKelompokProcess']);
     Route::post('/admin/kelompok/edit-setujui-process', [KelompokController::class, 'setujuiKelompok']);
     Route::get('/admin/kelompok/detail/{id}', [KelompokController::class, 'detailKelompok']);

     Route::get('/admin/kelompok/delete-mahasiswa-process/{id_mahasiswa}/{id}', [KelompokController::class, 'deleteKelompokMahasiswaProcess']);
     Route::get('/admin/kelompok/delete-dosen-process/{id_dosen}/{id}', [KelompokController::class, 'deleteKelompokDosenProcess']);


     //pendaftaran caps individu
     Route::get('/admin/pendaftaran', [PendaftaranController::class, 'index']);
     Route::get('/admin/pendaftaran/add', [PendaftaranController::class, 'addPendaftaran']);
     Route::post('/admin/pendaftaran/add-process', [PendaftaranController::class, 'addPendaftaranProcess']);
     Route::get('/admin/pendaftaran/update-mahasiswa-topik', [PendaftaranController::class, 'updateMhsTopikProcess']);


});

// mahasiswa
Route::middleware(['auth', 'role:03'])->group(function () {

    //mahasiswakelompok
    Route::get('/mahasiswa/kelompok', [MahasiswaKelompokController::class, 'index']);
    Route::post('/mahasiswa/kelompok/add-kelompok-process', [MahasiswaKelompokController::class, 'addKelompokProcess']);
    Route::post('/mahasiswa/kelompok/add-punya-kelompok-process', [MahasiswaKelompokController::class, 'addPunyaKelompokProcess']);

    Route::post('/mahasiswa/kelompok/terima-kelompok', [MahasiswaKelompokController::class, 'terimaKelompok'])->name('kelompok.accept');
    Route::post('/mahasiswa/kelompok/tolak-kelompok', [MahasiswaKelompokController::class, 'tolakKelompok'])->name('kelompok.reject');

    //mahasiswaFile
    Route::get('/mahasiswa/file-upload', [UploadFileController::class, 'index']);
    Route::post('/mahasiswa/file-upload/upload-makalah', [UploadFileController::class, 'uploadMakalahProcess']);
    Route::post('/mahasiswa/file-upload/upload-laporan', [UploadFileController::class, 'uploadLaporanProcess']);

    Route::post('/mahasiswa/file-upload/upload-c100', [UploadFileController::class, 'uploadC100Process']);
    Route::post('/mahasiswa/file-upload/upload-c200', [UploadFileController::class, 'uploadC200Process']);
    Route::post('/mahasiswa/file-upload/upload-c300', [UploadFileController::class, 'uploadC300Process']);
    Route::post('/mahasiswa/file-upload/upload-c400', [UploadFileController::class, 'uploadC400Process']);
    Route::post('/mahasiswa/file-upload/upload-c500', [UploadFileController::class, 'uploadC500Process']);

    Route::post('/mahasiswa/file-upload/add-kelompok-process', [UploadFileController::class, 'addKelompokProcess']);
    Route::post('/mahasiswa/file-upload/add-punya-kelompok-process', [UploadFileController::class, 'addPunyaKelompokProcess']);
    Route::get('/mahasiswa/file-upload/delete-process/{user_id}', [UploadFileController::class, 'deleteSiklusProcess']);
    Route::get('/mahasiswa/file-upload/edit/{user_id}', [UploadFileController::class, 'editSiklus']);
    Route::post('/mahasiswa/file-upload/edit-process', [UploadFileController::class, 'editSiklusProcess']);
    Route::get('/mahasiswa/file-upload/detail/{user_id}', [UploadFileController::class, 'detailSiklus']);

    // sidang proposal
    Route::get('/mahasiswa/sidang-proposal', [MahasiswaSidangProposalController::class, 'index']);


    //mahasiswa Expo
    Route::get('/mahasiswa/expo', [MahasiswaExpoController::class, 'index']);
    Route::post('/mahasiswa/expo/expo-daftar', [MahasiswaExpoController::class, 'daftarExpo']);


    Route::post('/mahasiswa/expo/add-kelompok-process', [MahasiswaExpoController::class, 'addKelompokProcess']);
    Route::post('/mahasiswa/expo/add-punya-kelompok-process', [MahasiswaExpoController::class, 'addPunyaKelompokProcess']);
    Route::get('/mahasiswa/expo/delete-process/{user_id}', [MahasiswaExpoController::class, 'deleteSiklusProcess']);
    Route::get('/mahasiswa/expo/edit/{user_id}', [MahasiswaExpoController::class, 'editSiklus']);
    Route::get('/mahasiswa/expo/detail/{user_id}', [MahasiswaExpoController::class, 'detailSiklus']);

});


// dosen
Route::middleware(['auth', 'role:04'])->group(function () {

     //halaman dosen
     Route::get('/dosen/bimbingan-saya', [BimbinganSayaController::class, 'index']);
     Route::get('/dosen/bimbingan-saya/terima/{id}', [BimbinganSayaController::class, 'terimaBimbinganSaya']);
     Route::get('/dosen/bimbingan-saya/tolak/{id}', [BimbinganSayaController::class, 'tolakBimbinganSaya']);
     Route::get('/dosen/bimbingan-saya/detail/{id}', [BimbinganSayaController::class, 'detailBimbinganSaya']);
     Route::get('/dosen/bimbingan-saya/terimatest/{status_dosen_pembimbing_1}', [BimbinganSayaController::class, 'terimaBimbinganSayaTest'])->name('dosen.bimbingan-saya.terima');;
     Route::get('/dosen/bimbingan-saya/tolaktest/{status_dosen_pembimbing_1}', [BimbinganSayaController::class, 'tolakBimbinganSayaTest'])->name('dosen.bimbingan-saya.tolak');;

     //halaman dosen
     Route::get('/dosen/pengujian', [PengujianController::class, 'index']);
     Route::get('/dosen/pengujian/terima/{id}', [PengujianController::class, 'terimaPengujian']);
     Route::get('/dosen/pengujian/tolak/{id}', [PengujianController::class, 'tolakPengujian']);
     Route::get('/dosen/pengujian/detail/{id}', [PengujianController::class, 'detailPengujian']);
     // Route::get('/dosen/bimbingan-saya/add', [BimbinganSayaController::class, 'addBimbinganSaya']);

});


Route::middleware(['auth'])->group(function () {

    // logout
    Route::get('/logout', [LogoutController::class, 'logout']);

    // Dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'index']);
    // --------------------------------------------------------------------------------------------

    // profil acccount
    Route::get('/admin/settings/account', [AccountController::class, 'index']);
    Route::post('/admin/settings/account/edit_process', [AccountController::class, 'editProcess']);
    Route::post('/admin/settings/account/edit_password', [AccountController::class, 'editPassword']);

    Route::post('/admin/settings/account/img_crop', [AccountController::class, 'ImgCrop'])->name('crop');

});
