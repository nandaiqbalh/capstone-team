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

/**
 * Manajer
 */

use App\Http\Controllers\Admin\Manajer\Branch\BranchController;
use App\Http\Controllers\Admin\Manajer\Branch\ItemPCController;
use App\Http\Controllers\Admin\Manajer\Master\AsetController;
// use App\Http\Controllers\Admin\Manajer\Register\AkunPCController as AkunManajerController;

/**
 * PJ
 */

use App\Http\Controllers\Admin\PJ\QRCode\QRCodeController;
use App\Http\Controllers\Admin\PJ\Register\AkunPCController;
use App\Http\Controllers\Admin\PJ\Register\AsetController as AsetPJController;
use App\Http\Controllers\Admin\PJ\Register\AcaraPCController;

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
     * PJ
     */

    // Cetak QR
    Route::get('/admin/pj/qr-code', [QRCodeController::class, 'index']);
    // Route::get('/admin/pj/qr-code/add', [QRCodeController::class,'add']);
    // Route::post('/admin/pj/qr-code/download-qr/{id}', [QRCodeController::class,'downloadQRCode']);
    // Route::get('/admin/pj/qr-code/download-qr/all', [QRCodeController::class,'downloadQRCodeAll']);
    // Route::get('/admin/pj/qr-code/detail/{id}', [QRCodeController::class,'detail']);
    // Route::get('/admin/pj/qr-code/edit/{id}', [QRCodeController::class,'edit']);
    // Route::post('/admin/pj/qr-code/edit-process', [QRCodeController::class,'editProcess']);
    // Route::get('/admin/pj/qr-code/delete-process/{id}', [QRCodeController::class,'deleteProcess']);
    // Route::get('/admin/pj/qr-code/search', [QRCodeController::class,'search']);


    // Manajer branch
    Route::get('/admin/manajer/cabang', [BranchController::class, 'index']);
    Route::get('/admin/manajer/cabang/add', [BranchController::class, 'add']);
    Route::post('/admin/manajer/cabang/add_process', [BranchController::class, 'addProcess']);
    Route::get('/admin/manajer/cabang/edit/{id}', [BranchController::class, 'edit']);
    Route::post('/admin/manajer/cabang/edit_process', [BranchController::class, 'editProcess']);
    Route::get('/admin/manajer/cabang/delete_process/{id}', [BranchController::class, 'deleteProcess']);
    Route::get('/admin/manajer/cabang/akun/{id}', [BranchController::class, 'addAkun']);
    Route::post('/admin/manajer/cabang/akun_add_process', [BranchController::class, 'addAkunProcess']);
    Route::get('/admin/manajer/cabang/akun/edit/{id}', [BranchController::class, 'editAkun']);
    Route::post('/admin/manajer/cabang/akun_edit_process', [BranchController::class, 'editAkunProcess']);
    Route::get('/admin/manajer/cabang/akun/delete_process/{id}', [BranchController::class, 'deleteAkunProcess']);
    Route::get('/admin/manajer/cabang/search', [BranchController::class, 'search']);


    // Manajer Aset
    Route::get('/admin/manajer/master/aset', [AsetController::class, 'index']);
    Route::get('/admin/manajer/master/aset/add', [AsetController::class, 'add']);
    Route::post('/admin/manajer/master/aset/add_process', [AsetController::class, 'addProcess']);
    Route::get('/admin/manajer/master/aset/edit/{id}', [AsetController::class, 'edit']);
    Route::post('/admin/manajer/master/aset/edit_process', [AsetController::class, 'editProcess']);
    Route::get('/admin/manajer/master/aset/detail/{id}', [AsetController::class, 'detail']);
    Route::get('/admin/manajer/master/aset/delete_process/{id}', [AsetController::class, 'deleteProcess']);
    Route::get('/admin/manajer/master/aset/search', [AsetController::class, 'search']);

    Route::get('/admin/manajer/master/aset-ketentuan', [AsetController::class, 'index']);


    // PJ Akun Bamasama
    Route::get('/admin/pj/register/akun', [AkunPCController::class, 'index']);
    Route::get('/admin/pj/register/akun/add', [AkunPCController::class, 'add']);
    Route::post('/admin/pj/register/akun/add-process', [AkunPCController::class, 'addProcess']);
    Route::get('/admin/pj/register/akun/detail/{id}', [AkunPCController::class, 'detail']);
    Route::get('/admin/pj/register/akun/edit/{id}', [AkunPCController::class, 'edit']);
    Route::post('/admin/pj/register/akun/edit-process', [AkunPCController::class, 'editProcess']);
    Route::get('/admin/pj/register/akun/delete-process/{id}', [AkunPCController::class, 'deleteProcess']);
    Route::get('/admin/pj/register/akun/search', [AkunPCController::class, 'search']);

    // PJ Item Bamasama
    Route::get('/admin/pj/register/aset', [AsetPJController::class, 'index']);
    Route::get('/admin/pj/register/aset/add', [AsetPJController::class, 'add']);
    Route::post('/admin/pj/register/aset/add-process', [AsetPJController::class, 'addProcess']);
    Route::get('/admin/pj/register/aset/detail/{id}', [AsetPJController::class, 'detail']);
    Route::get('/admin/pj/register/aset/edit/{id}', [AsetPJController::class, 'edit']);
    Route::post('/admin/pj/register/aset/edit-process', [AsetPJController::class, 'editProcess']);
    Route::get('/admin/pj/register/aset/delete-process/{id}', [AsetPJController::class, 'deleteProcess']);
    Route::get('/admin/pj/register/aset/search', [AsetPJController::class, 'search']);


    // PJ Acara PC
    Route::get('/admin/pj/register/acara', [AcaraPCController::class, 'index']);
    Route::get('/admin/pj/register/acara/add', [AcaraPCController::class, 'add']);
    Route::post('/admin/pj/register/acara/add-process', [AcaraPCController::class, 'addProcess']);
    Route::get('/admin/pj/register/acara/add-detail/{id}', [AcaraPCController::class, 'addDetail']);
    Route::post('/admin/pj/register/acara/add-detail-process-guest', [AcaraPCController::class, 'addDetailProcessGuest']);
    Route::post('/admin/pj/register/acara/add-detail-process-ticket', [AcaraPCController::class, 'addDetailProcessTicket']);
    Route::post('/admin/pj/register/acara/add-detail-process-rundown', [AcaraPCController::class, 'addDetailProcessRundown']);
    Route::get('/admin/pj/register/acara/detail/{id}', [AcaraPCController::class, 'detail']);
    Route::get('/admin/pj/register/acara/edit/{id}', [AcaraPCController::class, 'edit']);
    Route::post('/admin/pj/register/acara/edit-process', [AcaraPCController::class, 'editProcess']);
    Route::get('/admin/pj/register/acara/delete-process/{id}', [AcaraPCController::class, 'deleteProcess']);
    Route::get('/admin/pj/register/acara/delete-guest-process/{id}', [AcaraPCController::class, 'deleteGuestProcess']);
    Route::get('/admin/pj/register/acara/delete-ticket-process/{id}', [AcaraPCController::class, 'deleteTicketProcess']);
    Route::get('/admin/pj/register/acara/delete-rundown-process/{id}', [AcaraPCController::class, 'deleteRundownProcess']);
    Route::get('/admin/pj/register/acara/search', [AcaraPCController::class, 'search']);

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
});
