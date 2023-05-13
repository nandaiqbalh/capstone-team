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
use App\Http\Controllers\Admin\Dosen\DosenController;
use App\Http\Controllers\Admin\Siklus\SiklusController;
use App\Http\Controllers\Admin\Broadcast\BroadcastController;
use App\Http\Controllers\Admin\Kelompok\KelompokController;

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

    //dosen
    Route::get('/admin/dosen', [DosenController::class, 'index']);
    Route::get('/admin/dosen/add', [DosenController::class, 'addDosen']);
    Route::post('/admin/dosen/add-process', [DosenController::class, 'addDosenProcess']);
    Route::get('/admin/dosen/delete-process/{user_id}', [DosenController::class, 'deleteDosenProcess']);
    Route::get('/admin/dosen/edit/{user_id}', [DosenController::class, 'editDosen']);
    Route::post('/admin/dosen/edit-process', [DosenController::class, 'editDosenProcess']);
    Route::get('/admin/dosen/detail/{user_id}', [DosenController::class, 'detailDosen']);

    //siklus
    Route::get('/admin/siklus', [SiklusController::class, 'index']);
    Route::get('/admin/siklus/add', [SiklusController::class, 'addSiklus']);
    Route::post('/admin/siklus/add-process', [SiklusController::class, 'addSiklusProcess']);
    Route::get('/admin/siklus/delete-process/{user_id}', [SiklusController::class, 'deleteSiklusProcess']);
    Route::get('/admin/siklus/edit/{user_id}', [SiklusController::class, 'editSiklus']);
    Route::post('/admin/siklus/edit-process', [SiklusController::class, 'editSiklusProcess']);
    Route::get('/admin/siklus/detail/{user_id}', [SiklusController::class, 'detailSiklus']);

    //broadcast
    Route::get('/admin/broadcast', [BroadcastController::class, 'index']);
    // Route::get('/admin/siklus/add', [SiklusController::class, 'addSiklus']);
    // Route::post('/admin/siklus/add-process', [SiklusController::class, 'addSiklusProcess']);
    // Route::get('/admin/siklus/delete-process/{user_id}', [SiklusController::class, 'deleteSiklusProcess']);
    // Route::get('/admin/siklus/edit/{user_id}', [SiklusController::class, 'editSiklus']);
    // Route::post('/admin/siklus/edit-process', [SiklusController::class, 'editSiklusProcess']);
    // Route::get('/admin/siklus/detail/{user_id}', [SiklusController::class, 'detailSiklus']);

    //kelompok
    Route::get('/admin/mahasiswa/kelompok', [KelompokController::class, 'index']);
    Route::get('/admin/mahasiswa/kelompok/add', [SKelompokontroller::class, 'addSiklus']);
    Route::post('/admin/mahasiswa/kelompok/add-process', [KelompokController::class, 'addSiklusProcess']);
    Route::get('/admin/mahasiswa/kelompok/delete-process/{user_id}', [KelompokController::class, 'deleteSiklusProcess']);
    Route::get('/admin/mahasiswa/kelompok/edit/{user_id}', [KelompokController::class, 'editSiklus']);
    Route::post('/admin/mahasiswa/kelompok/edit-process', [KelompokController::class, 'editSiklusProcess']);
    Route::get('/admin/mahasiswa/kelompok/detail/{user_id}', [KelompokController::class, 'detailSiklus']);
});
