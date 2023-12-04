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

use App\Http\Controllers\Admin\DashboardController;


use App\Http\Controllers\Admin\Settings\RoleController;
use App\Http\Controllers\Admin\Settings\MenuController;
use App\Http\Controllers\Admin\Settings\AccountController;
use App\Http\Controllers\Admin\Settings\AccountsController;
use App\Http\Controllers\Admin\Settings\SmtpController;
use App\Http\Controllers\Admin\Settings\RestApiController;
use App\Http\Controllers\Admin\Settings\ContohHalamanController;
use App\Http\Controllers\Admin\Settings\LogsController;
use App\Http\Controllers\Admin\Settings\TakeOverLoginController;

use App\Http\Controllers\User\Home\HomeController;
use App\Http\Controllers\Admin\Mahasiswa\MahasiswaController;
use App\Http\Controllers\Admin\Topik\TimCapstoneController;
use App\Http\Controllers\Admin\Topik\TopikController;
use App\Http\Controllers\Admin\Dosen\DosenController;
use App\Http\Controllers\Admin\Siklus\SiklusController;
use App\Http\Controllers\Admin\Broadcast\BroadcastController;
use App\Http\Controllers\Admin\JadwalPendaftaranKelompok\JadwalPendaftaranKelompokController;
use App\Http\Controllers\Admin\JadwalSidangProposal\JadwalSidangProposalController;
use App\Http\Controllers\Admin\JadwalExpo\JadwalExpoController;
use App\Http\Controllers\Admin\Kelompok\KelompokController;
use App\Http\Controllers\Admin\Pendaftaran\PendaftaranController;

use App\Http\Controllers\Mahasiswa\Kelompok_Mahasiswa\MahasiswaKelompokController;
use App\Http\Controllers\Mahasiswa\Expo_Mahasiswa\MahasiswaExpoController;

use App\Http\Controllers\Admin\UploadFile\UploadFileController;

// use App\Http\Controllers\Mahasiswa\Kelompok\MahasiswaKelompokController;
use App\Http\Controllers\Dosen\Bimbingan_Saya\BimbinganSayaController;
use App\Http\Controllers\Dosen\Pengujian\PengujianController;



/**
 * PUBLIC
 */
Route::get('', [LoginController::class, 'index'])->name('/login')->middleware('guest');
Route::get('/home', [HomeController::class, 'index'])->name('/index')->middleware('guest');
Route::get('/event', [HomeController::class, 'event']);
Route::get('/event-{id}', [HomeController::class, 'eventDetail']);
Route::get('/ticket-{id}', [HomeController::class, 'eventTicketBuy']);
Route::post('/process-ticket', [HomeController::class, 'pesanTicket']);
Route::get('/pesan-{id}', [HomeController::class, 'pesanTicket']);
// Route::get('', [LoginController::class,'index'])->name('/login')->middleware('guest');

/**
 * AUTH
 */
Route::get('/login', [LoginController::class, 'index'])->name('/login')->middleware('guest');
Route::post('/login/process', [LoginController::class, 'authenticate']);
Route::get('/lupa-password', [ResetPasswordController::class, 'index']);
Route::post('/lupa-password/process', [ResetPasswordController::class, 'resetPasswordProcess']);
Route::get('/ubah-password', [ResetPasswordController::class, 'ubahPassword']);
Route::post('/ubah-password/process', [ResetPasswordController::class, 'ubahPasswordProcess']);
/**
 * ADMIN
 */
Route::middleware(['auth'])->group(function () {

    // logout
    Route::get('/logout', [LogoutController::class, 'logout']);

    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
    // --------------------------------------------------------------------------------------------



    // --------------------------------------------------------------------------------------------
    /**
     * SETTINGS
     */
    // role
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
    // smtp
    Route::get('/admin/settings/smtp', [SmtpController::class, 'index']);
    Route::post('/admin/settings/smtp/edit_process', [SmtpController::class, 'editProcess']);

    // profil acccount
    Route::get('/admin/settings/account', [AccountController::class, 'index']);
    Route::post('/admin/settings/account/edit_process', [AccountController::class, 'editProcess']);
    Route::post('/admin/settings/account/edit_password', [AccountController::class, 'editPassword']);

    Route::post('/admin/settings/account/img_crop', [AccountController::class, 'ImgCrop'])->name('crop');

    // rest api
    Route::get('/admin/settings/rest-api', [RestApiController::class, 'index']);
    Route::get('/admin/settings/rest-api/search', [RestApiController::class, 'search']);
    Route::get('/admin/settings/rest-api/delete_process/{id}', [RestApiController::class, 'deleteProcess']);

    // contoh halaman
    Route::get('/admin/settings/contoh-halaman', [ContohHalamanController::class, 'index']);
    Route::get('/admin/settings/contoh-halaman/add', [ContohHalamanController::class, 'add']);
    Route::post('/admin/settings/contoh-halaman/add-process', [ContohHalamanController::class, 'addProcess']);
    Route::get('/admin/settings/contoh-halaman/detail/{id}', [ContohHalamanController::class, 'detail']);
    Route::get('/admin/settings/contoh-halaman/edit/{id}', [ContohHalamanController::class, 'edit']);
    Route::post('/admin/settings/contoh-halaman/edit-process', [ContohHalamanController::class, 'editProcess']);
    Route::get('/admin/settings/contoh-halaman/delete-process/{id}', [ContohHalamanController::class, 'deleteProcess']);
    Route::get('/admin/settings/contoh-halaman/search', [ContohHalamanController::class, 'search']);

    // logs
    Route::get('/admin/settings/logs', [LogsController::class, 'index']);
    Route::get('/admin/settings/logs/search', [LogsController::class, 'search']);

    Route::get('/admin/settings/logs/login', [LogsController::class, 'indexLogin']);
    Route::get('/admin/settings/logs/login/search', [LogsController::class, 'searchLogin']);

    Route::get('/admin/settings/logs/login-attempt', [LogsController::class, 'indexLoginAttempt']);
    Route::get('/admin/settings/logs/login-attempt/search', [LogsController::class, 'searchLoginAttempt']);

    Route::get('/admin/settings/logs/reset-password', [LogsController::class, 'indexResetPassword']);
    Route::get('/admin/settings/logs/reset-password/search', [LogsController::class, 'searchResetPassword']);

    // take over login
    Route::get('admin/settings/take-over-login', [TakeOverLoginController::class, 'takeOverProcess']);

    //kapan pake get dan post
    //post:mencantumkans csrf_field(), ketika request data penting spt input data dr client ke server
    //get:

    //mahasiswa
    Route::get('/admin/mahasiswa', [MahasiswaController::class, 'index']);
    Route::get('/admin/mahasiswa/add', [MahasiswaController::class, 'addMahasiswa']);
    Route::post('/admin/mahasiswa/add-process', [MahasiswaController::class, 'addMahasiswaProcess']);
    Route::get('/admin/mahasiswa/delete-process/{user_id}', [MahasiswaController::class, 'deleteMahasiswaProcess']);
    Route::get('/admin/mahasiswa/edit/{user_id}', [MahasiswaController::class, 'editMahasiswa']);
    Route::post('/admin/mahasiswa/edit-process', [MahasiswaController::class, 'editMahasiswaProcess']);
    Route::get('/admin/mahasiswa/detail/{user_id}', [MahasiswaController::class, 'detailMahasiswa']);
    Route::get('/admin/mahasiswa/search', [MahasiswaController::class, 'searchMahasiswa']);

    //tim capstone
    Route::get('/admin/tim-capstone', [TimCapstoneController::class, 'index']);
    Route::get('/admin/tim-capstone/add', [TimCapstoneController::class, 'addTimCapstone']);
    Route::post('/admin/tim-capstone/add-process', [TimCapstoneController::class, 'addTimCapstoneProcess']);
    Route::get('/admin/tim-capstone/delete-process/{user_id}', [TimCapstoneController::class, 'deleteTimCapstoneProcess']);
    Route::get('/admin/tim-capstone/edit/{user_id}', [TimCapstoneController::class, 'editTimCapstone']);
    Route::post('/admin/tim-capstone/edit-process', [TimCapstoneController::class, 'editTimCapstoneProcess']);
    Route::get('/admin/tim-capstone/detail/{user_id}', [TimCapstoneController::class, 'detailTimCapstone']);

    //topik
    Route::get('/admin/topik', [TopikController::class, 'index']);
    Route::get('/admin/topik/add', [TopikController::class, 'addTopik']);
    Route::post('/admin/topik/add-process', [TopikController::class, 'addTopikProcess']);
    Route::get('/admin/topik/delete-process/{id}', [TopikController::class, 'deleteTopikProcess']);
    Route::get('/admin/topik/edit/{id}', [TopikController::class, 'editTopik']);
    Route::post('/admin/topik/edit-process', [TopikController::class, 'editTopikProcess']);


    //dosen
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

    //pendaftaran
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
    // Route::get('/admin/kelompok/edit/{user_id}', [PendaftaranController::class, 'editSiklus']);
    // Route::post('/admin/kelompok/edit-process', [PendaftaranController::class, 'editSiklusProcess']);
    // Route::get('/admin/kelompok/detail/{id}', [PendaftaranController::class, 'detailKelompok']);

    //mahasiswakelompok
    Route::get('/mahasiswa/kelompok', [MahasiswaKelompokController::class, 'index']);
    Route::post('/mahasiswa/kelompok/add-kelompok-process', [MahasiswaKelompokController::class, 'addKelompokProcess']);
    Route::post('/mahasiswa/kelompok/add-punya-kelompok-process', [MahasiswaKelompokController::class, 'addPunyaKelompokProcess']);
    Route::get('/mahasiswa/kelompok/delete-process/{user_id}', [MahasiswaKelompokController::class, 'deleteSiklusProcess']);
    Route::get('/mahasiswa/kelompok/edit/{user_id}', [MahasiswaKelompokController::class, 'editSiklus']);
    Route::post('/mahasiswa/kelompok/edit-process', [MahasiswaKelompokController::class, 'editSiklusProcess']);
    Route::get('/mahasiswa/kelompok/detail/{user_id}', [MahasiswaKelompokController::class, 'detailSiklus']);

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

    //mahasiswa Expo
    Route::get('/mahasiswa/expo', [MahasiswaExpoController::class, 'index']);
    Route::post('/mahasiswa/expo/daftar-process/{id}', [MahasiswaExpoController::class, 'daftar']);
    Route::post('/mahasiswa/expo/edit-process', [MahasiswaExpoController::class, 'editProcess']);


    Route::post('/mahasiswa/expo/add-kelompok-process', [MahasiswaExpoController::class, 'addKelompokProcess']);
    Route::post('/mahasiswa/expo/add-punya-kelompok-process', [MahasiswaExpoController::class, 'addPunyaKelompokProcess']);
    Route::get('/mahasiswa/expo/delete-process/{user_id}', [MahasiswaExpoController::class, 'deleteSiklusProcess']);
    Route::get('/mahasiswa/expo/edit/{user_id}', [MahasiswaExpoController::class, 'editSiklus']);
    Route::get('/mahasiswa/expo/detail/{user_id}', [MahasiswaExpoController::class, 'detailSiklus']);

    //halaman dosen
    Route::get('/dosen/bimbingan-saya', [BimbinganSayaController::class, 'index']);
    Route::get('/dosen/bimbingan-saya/terima/{id}', [BimbinganSayaController::class, 'terimaBimbinganSaya']);
    Route::get('/dosen/bimbingan-saya/tolak/{id}', [BimbinganSayaController::class, 'tolakBimbinganSaya']);
    Route::get('/dosen/bimbingan-saya/detail/{id}', [BimbinganSayaController::class, 'detailBimbinganSaya']);

    //halaman dosen
    Route::get('/dosen/pengujian', [PengujianController::class, 'index']);
    Route::get('/dosen/pengujian/terima/{id}', [PengujianController::class, 'terimaPengujian']);
    Route::get('/dosen/pengujian/tolak/{id}', [PengujianController::class, 'tolakPengujian']);
    Route::get('/dosen/pengujian/detail/{id}', [PengujianController::class, 'detailPengujian']);
    // Route::get('/dosen/bimbingan-saya/add', [BimbinganSayaController::class, 'addBimbinganSaya']);


});
