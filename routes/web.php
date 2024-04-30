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
use App\Http\Controllers\TimCapstone\Peminatan\PeminatanController;
use App\Http\Controllers\TimCapstone\RuangSidang\RuangSidangController;
use App\Http\Controllers\TimCapstone\Dosen\DosenController;
use App\Http\Controllers\TimCapstone\Balancing\PembimbingKelompok\PembimbingKelompokController;
use App\Http\Controllers\TimCapstone\Balancing\PembimbingMahasiswa\PembimbingMahasiswaController;
use App\Http\Controllers\TimCapstone\Balancing\PengujiProposal\PengujiProposalController;
use App\Http\Controllers\TimCapstone\Balancing\PengujiTA\PengujiTAController;
use App\Http\Controllers\TimCapstone\Siklus\SiklusController;
use App\Http\Controllers\TimCapstone\Broadcast\BroadcastController;
use App\Http\Controllers\TimCapstone\SidangProposal\JadwalSidangProposal\JadwalSidangProposalController;
use App\Http\Controllers\TimCapstone\SidangProposal\PenjadwalanSidangProposal\PenjadwalanSidangProposalController;
use App\Http\Controllers\TimCapstone\ExpoProject\ExpoProjectController;
use App\Http\Controllers\TimCapstone\SidangTA\SidangTA\SidangTAController;
use App\Http\Controllers\TimCapstone\SidangTA\JadwalSidangTA\JadwalSidangTAController;
use App\Http\Controllers\TimCapstone\Kelompok\KelompokValid\KelompokValidController;
use App\Http\Controllers\TimCapstone\Kelompok\PenetapanAnggota\PenetapanAnggotaController;
use App\Http\Controllers\TimCapstone\Kelompok\PenetapanDosbing\PenetapanDosbingController;
use App\Http\Controllers\TimCapstone\Kelompok\ValidasiKelompok\ValidasiKelompokController;

// mahasiswa
use App\Http\Controllers\Mahasiswa\Kelompok_Mahasiswa\MahasiswaKelompokController;
use App\Http\Controllers\Mahasiswa\SidangProposal_Mahasiswa\MahasiswaSidangProposalController;
use App\Http\Controllers\Mahasiswa\Expo_Mahasiswa\MahasiswaExpoController;
use App\Http\Controllers\Mahasiswa\TugasAkhir_Mahasiswa\MahasiswaTugasAkhirController;

use App\Http\Controllers\Mahasiswa\Dokumen_Mahasiswa\DokumenMahasiswaController;

// use App\Http\Controllers\Mahasiswa\Kelompok\MahasiswaKelompokController;
use App\Http\Controllers\Dosen\KelompokBimbingan\KelompokBimbinganController;
use App\Http\Controllers\Dosen\MahasiswaBimbingan\MahasiswaBimbinganController;
use App\Http\Controllers\Dosen\PersetujuanC100\PersetujuanC100Controller;
use App\Http\Controllers\Dosen\PersetujuanC200\PersetujuanC200Controller;
use App\Http\Controllers\Dosen\PersetujuanC300\PersetujuanC300Controller;
use App\Http\Controllers\Dosen\PersetujuanC400\PersetujuanC400Controller;
use App\Http\Controllers\Dosen\PersetujuanC500\PersetujuanC500Controller;
use App\Http\Controllers\Dosen\PersetujuanLaporanTA\PersetujuanLaporanTAController;
use App\Http\Controllers\Dosen\PersetujuanMakalahTA\PersetujuanMakalahTAController;
use App\Http\Controllers\Dosen\PengujianProposal\PengujianProposalController;
use App\Http\Controllers\Dosen\PengujianTA\PengujianTAController;


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
    Route::post('/admin/settings/accounts/import-user', [AccountsController::class, 'import']);

     // take over login
    Route::get('admin/settings/take-over-login', [TakeOverLoginController::class, 'takeOverProcess']);

});

// tim capstone
 Route::middleware(['auth', 'role:02'])->group(function () {

    // beranda
    Route::get('tim-capstone/beranda', [DashboardController::class, 'indexTimCapstone']);
    Route::get('tim-capstone/beranda/filter-siklus', [DashboardController::class, 'filterSiklusByTimCapstone']);

     //mahasiswa
     Route::get('/tim-capstone/mahasiswa', [MahasiswaController::class, 'index']);
     Route::get('/tim-capstone/mahasiswa/add', [MahasiswaController::class, 'addMahasiswa']);
     Route::post('/tim-capstone/mahasiswa/add-process', [MahasiswaController::class, 'addMahasiswaProcess']);
     Route::get('/tim-capstone/mahasiswa/delete-process/{user_id}', [MahasiswaController::class, 'deleteMahasiswaProcess']);
     Route::get('/tim-capstone/mahasiswa/edit/{user_id}', [MahasiswaController::class, 'editMahasiswa']);
     Route::post('/tim-capstone/mahasiswa/edit-process', [MahasiswaController::class, 'editMahasiswaProcess']);
     Route::get('/tim-capstone/mahasiswa/detail/{user_id}', [MahasiswaController::class, 'detailMahasiswa']);
     Route::get('/tim-capstone/mahasiswa/search', [MahasiswaController::class, 'searchMahasiswa']);


     //topik
     Route::get('/tim-capstone/topik', [TopikController::class, 'index']);
     Route::get('/tim-capstone/topik/add', [TopikController::class, 'addTopik']);
     Route::post('/tim-capstone/topik/add-process', [TopikController::class, 'addTopikProcess']);
     Route::get('/tim-capstone/topik/delete-process/{id}', [TopikController::class, 'deleteTopikProcess']);
     Route::get('/tim-capstone/topik/edit/{id}', [TopikController::class, 'editTopik']);
     Route::post('/tim-capstone/topik/edit-process', [TopikController::class, 'editTopikProcess']);

     //topik
     Route::get('/tim-capstone/peminatan', [PeminatanController::class, 'index']);
     Route::get('/tim-capstone/peminatan/add', [PeminatanController::class, 'addPeminatan']);
     Route::post('/tim-capstone/peminatan/add-process', [PeminatanController::class, 'addPeminatanProcess']);
     Route::get('/tim-capstone/peminatan/delete-process/{id}', [PeminatanController::class, 'deletePeminatanProcess']);
     Route::get('/tim-capstone/peminatan/edit/{id}', [PeminatanController::class, 'editPeminatan']);
     Route::post('/tim-capstone/peminatan/edit-process', [PeminatanController::class, 'editPeminatanProcess']);

     //ruang sidang
     Route::get('/tim-capstone/ruangan', [RuangSidangController::class, 'index']);
     Route::get('/tim-capstone/ruangan/add', [RuangSidangController::class, 'create']);
     Route::post('/tim-capstone/ruangan/add-process', [RuangSidangController::class, 'store']);
     Route::get('/tim-capstone/ruangan/delete-process/{id}', [RuangSidangController::class, 'delete']);
     Route::get('/tim-capstone/ruangan/edit/{id}', [RuangSidangController::class, 'edit']);
     Route::post('/tim-capstone/ruangan/edit-process', [RuangSidangController::class, 'update']);

     //dosen
     Route::get('/tim-capstone/dosen/dosen-to-aktif-1/{id}', [DosenController::class, 'toAktifKetersediaan1'])->name('to.aktif.ketersediaan.1');
     Route::get('/tim-capstone/dosen/dosen-to-inaktif-1/{id}', [DosenController::class, 'toInaktifKetersediaan1'])->name('to.inaktif.ketersediaan.1');
     Route::get('/tim-capstone/dosen/dosen-to-aktif-2/{id}', [DosenController::class, 'toAktifKetersediaan2'])->name('to.aktif.ketersediaan.2');
     Route::get('/tim-capstone/dosen/dosen-to-inaktif-2/{id}', [DosenController::class, 'toInaktifKetersediaan2'])->name('to.inaktif.ketersediaan.2');
     Route::get('/tim-capstone/dosen', [DosenController::class, 'index']);
     Route::get('/tim-capstone/dosen/add', [DosenController::class, 'addDosen']);
     Route::post('/tim-capstone/dosen/add-process', [DosenController::class, 'addDosenProcess']);
     Route::get('/tim-capstone/dosen/delete-process/{user_id}', [DosenController::class, 'deleteDosenProcess']);
     Route::get('/tim-capstone/dosen/edit/{user_id}', [DosenController::class, 'editDosen']);
     Route::post('/tim-capstone/dosen/edit-process', [DosenController::class, 'editDosenProcess']);
     Route::get('/tim-capstone/dosen/detail/{user_id}', [DosenController::class, 'detailDosen']);
     Route::get('/tim-capstone/dosen/search', [DosenController::class, 'searchDosen']);

     // balancing dosen pembimbing
     Route::get('/tim-capstone/balancing-dosbing-kelompok', [PembimbingKelompokController::class, 'balancingDosbingKelompok']);
     Route::get('/tim-capstone/balancing-dosbing-kelompok/filter-siklus', [PembimbingKelompokController::class, 'filterBalancingDosbingKelompok']);
     Route::get('/tim-capstone/balancing-dosbing-kelompok/detail/{user_id}', [PembimbingKelompokController::class, 'detailBalancingDosbingKelompok']);
     Route::get('/tim-capstone/balancing-dosbing-kelompok/search', [PembimbingKelompokController::class, 'searchBalancingDosbingKelompok']);

     // balancing dosen pembimbing mahasiswa
     Route::get('/tim-capstone/balancing-dosbing-mahasiswa', [PembimbingMahasiswaController::class, 'balancingDosbingMahasiswa']);
     Route::get('/tim-capstone/balancing-dosbing-mahasiswa/filter-siklus', [PembimbingMahasiswaController::class, 'filterBalancingDosbingMahasiswa']);
     Route::get('/tim-capstone/balancing-dosbing-mahasiswa/detail/{user_id}', [PembimbingMahasiswaController::class, 'detailBalancingDosbingMahasiswa']);
     Route::get('/tim-capstone/balancing-dosbing-mahasiswa/search', [PembimbingMahasiswaController::class, 'searchBalancingDosbingMahasiswa']);
     Route::get('/tim-capstone/balancing-dosbing-mahasiswa/detail-mahasiswa/{user_id}', [PembimbingMahasiswaController::class, 'detailMahasiswa']);

    // balancing dosen penguji proposal
     Route::get('/tim-capstone/balancing-penguji-proposal', [PengujiProposalController::class, 'balancingPengujiProposal']);
     Route::get('/tim-capstone/balancing-penguji-proposal/filter-siklus', [PengujiProposalController::class, 'filterBalancingPengujiProposal']);
     Route::get('/tim-capstone/balancing-penguji-proposal/detail/{user_id}', [PengujiProposalController::class, 'detailBalancingPengujiProposal']);
     Route::get('/tim-capstone/balancing-penguji-proposal/search', [PengujiProposalController::class, 'searchBalancingPengujiProposal']);

    // balancing dosen penguji proposal
    Route::get('/tim-capstone/balancing-penguji-ta', [PengujiTAController::class, 'balancingPengujiTA']);
    Route::get('/tim-capstone/balancing-penguji-ta/filter-periode', [PengujiTAController::class, 'filterBalancingPengujiTA']);
    Route::get('/tim-capstone/balancing-penguji-ta/detail/{user_id}', [PengujiTAController::class, 'detailBalancingPengujiTA']);
    Route::get('/tim-capstone/balancing-penguji-ta/detail-mahasiwa/{user_id}', [PengujiTAController::class, 'detailMahasiswa']);
    Route::get('/tim-capstone/balancing-penguji-ta/search', [PengujiTAController::class, 'searchBalancingPengujiTA']);

     //siklus
     Route::get('/tim-capstone/siklus', [SiklusController::class, 'index']);
     Route::get('/tim-capstone/siklus/add', [SiklusController::class, 'addSiklus']);
     Route::post('/tim-capstone/siklus/add-process', [SiklusController::class, 'addSiklusProcess']);
     Route::get('/tim-capstone/siklus/delete-process/{id}', [SiklusController::class, 'deleteSiklusProcess']);
     Route::get('/tim-capstone/siklus/edit/{id}', [SiklusController::class, 'editSiklus']);
     Route::post('/tim-capstone/siklus/edit-process', [SiklusController::class, 'editSiklusProcess']);
     Route::get('/tim-capstone/siklus/detail/{id}', [SiklusController::class, 'detailSiklus']);

     //broadcast
     Route::get('/tim-capstone/broadcast', [BroadcastController::class, 'index']);
     Route::get('/tim-capstone/broadcast/add', [BroadcastController::class, 'addBroadcast']);
     Route::post('/tim-capstone/broadcast/add-process', [BroadcastController::class, 'addBroadcastProcess']);
     Route::get('/tim-capstone/broadcast/delete-process/{id}', [BroadcastController::class, 'deleteBroadcastProcess']);
     Route::get('/tim-capstone/broadcast/edit/{user_id}', [BroadcastController::class, 'editBroadcast']);
     Route::post('/tim-capstone/broadcast/edit-process', [BroadcastController::class, 'editBroadcastProcess']);
     Route::get('/tim-capstone/broadcast/detail/{user_id}', [BroadcastController::class, 'detailBroadcast']);

     // penetapan kelompok
     Route::get('/tim-capstone/penetapan-anggota', [PenetapanAnggotaController::class, 'index']);
     Route::get('/tim-capstone/penetapan-anggota/add', [PenetapanAnggotaController::class, 'addPenetapanAnggota']);
     Route::get('/tim-capstone/penetapan-anggota/search', [PenetapanAnggotaController::class, 'searchMahasiswa']);
     Route::post('/tim-capstone/penetapan-anggota/add-process', [PenetapanAnggotaController::class, 'addPenetapanAnggotaProcess']);

    // penetapan dosbing kelompok
    Route::get('/tim-capstone/penetapan-dosbing', [PenetapanDosbingController::class, 'index']);
    Route::get('/tim-capstone/penetapan-dosbing/detail/{id}', [PenetapanDosbingController::class, 'detailKelompok']);
    //add delete dosen pembimbing
    Route::get('/tim-capstone/penetapan-dosbing/add-dosen-kelompok', [PenetapanDosbingController::class, 'addDosenKelompok']);
    Route::get('/tim-capstone/penetapan-dosbing/delete-dosen-process/{id_dosen}/{id_kelompok}', [PenetapanDosbingController::class, 'deleteDosenKelompok']);

    // validasi kelompok
    Route::get('/tim-capstone/validasi-kelompok', [ValidasiKelompokController::class, 'index']);
    Route::get('/tim-capstone/validasi-kelompok/detail/{id}', [ValidasiKelompokController::class, 'detailKelompok']);
    // edit dan delete kelompok
    Route::post('/tim-capstone/validasi-kelompok/setujui-kelompok-process', [ValidasiKelompokController::class, 'setujuiKelompokProcess']);
    Route::post('/tim-capstone/validasi-kelompok/edit-kelompok-process', [ValidasiKelompokController::class, 'editKelompokProcess']);
    Route::get('/tim-capstone/validasi-kelompok/delete-process/{id}', [ValidasiKelompokController::class, 'deleteKelompokProcess']);
    // add/delete dosen & mahasiswa
    Route::get('/tim-capstone/validasi-kelompok/add-mahasiswa-kelompok', [ValidasiKelompokController::class, 'addMahasiswaKelompok']);
    Route::get('/tim-capstone/validasi-kelompok/add-dosen-kelompok', [ValidasiKelompokController::class, 'addDosenKelompok']);
    Route::get('/tim-capstone/validasi-kelompok/delete-mahasiswa-process/{id_mahasiswa}/{id}', [ValidasiKelompokController::class, 'deleteMahasiswaKelompokProcess']);
    Route::get('/tim-capstone/validasi-kelompok/delete-dosen-process/{id_dosen}/{id}', [ValidasiKelompokController::class, 'deleteDosenKelompokProcess']);

    //kelompok valid
    Route::get('/tim-capstone/kelompok-valid', [KelompokValidController::class, 'index']);
    Route::get('/tim-capstone/kelompok-valid/filter-siklus', [KelompokValidController::class, 'filterSiklusKelompok']);
    Route::get('/tim-capstone/kelompok-valid/search', [KelompokValidController::class, 'search']);
    Route::get('/tim-capstone/kelompok-valid/delete-process/{id}', [KelompokValidController::class, 'deleteKelompokProcess']);
    Route::get('/tim-capstone/kelompok-valid/detail/{id}', [KelompokValidController::class, 'detailKelompok']);
    Route::get('/tim-capstone/kelompok-valid/delete-mahasiswa-process/{id_mahasiswa}/{id}', [KelompokValidController::class, 'deleteMahasiswaKelompokProcess']);


    // add sidang proposal
    Route::get('tim-capstone/penjadwalan-sidang-proposal', [PenjadwalanSidangProposalController::class, 'index']);
    Route::get('tim-capstone/penjadwalan-sidang-proposal/jadwalkan-sidang-proposal/{id}', [PenjadwalanSidangProposalController::class, 'detailKelompok']);
    Route::get('/tim-capstone/penjadwalan-sidang-proposal/filter-siklus', [PenjadwalanSidangProposalController::class, 'filterSiklusKelompok']);
    Route::get('/tim-capstone/penjadwalan-sidang-proposal/search', [PenjadwalanSidangProposalController::class, 'search']);

    //add delete dosen pembimbing
    Route::get('tim-capstone/penjadwalan-sidang-proposal/add-dosen-penguji', [PenjadwalanSidangProposalController::class, 'addDosenKelompok']);
    Route::get('tim-capstone/penjadwalan-sidang-proposal/delete-dosen-penguji/{id_dosen}/{id_kelompok}', [PenjadwalanSidangProposalController::class, 'deleteDosenKelompok']);
    // jadwalkan sidang proposal
    Route::post('tim-capstone/penjadwalan-sidang-proposal/add-jadwal-process', [PenjadwalanSidangProposalController::class, 'addJadwalProcess']);
    // detail

    //sidang proposal
    Route::get('/tim-capstone/jadwal-sidang-proposal', [JadwalSidangProposalController::class, 'index']);
    Route::get('/tim-capstone/jadwal-sidang-proposal/delete-process/{id}', [JadwalSidangProposalController::class, 'deleteJadwalSidangProposalProcess']);
    Route::get('/tim-capstone/jadwal-sidang-proposal/to-lulus/{id}', [JadwalSidangProposalController::class, 'toLulusSidangProposal']);
    Route::get('/tim-capstone/jadwal-sidang-proposal/to-gagal/{id}', [JadwalSidangProposalController::class, 'toGagalSidangProposal']);
    Route::get('/tim-capstone/jadwal-sidang-proposal/detail/{id}', [JadwalSidangProposalController::class, 'detailKelompok']);
    Route::get('/tim-capstone/jadwal-sidang-proposal/filter-siklus', [JadwalSidangProposalController::class, 'filterSiklusKelompok']);
    Route::get('/tim-capstone/jadwal-sidang-proposal/search', [JadwalSidangProposalController::class, 'search']);

    //expo
    Route::get('/tim-capstone/expo-project', [ExpoProjectController::class, 'index']);
    Route::get('/tim-capstone/expo-project/add', [ExpoProjectController::class, 'addExpoProject']);
    Route::post('/tim-capstone/expo-project/add-process', [ExpoProjectController::class, 'addExpoProjectProcess']);
    Route::get('/tim-capstone/expo-project/edit/{id}', [ExpoProjectController::class, 'editExpoProject']);
    Route::post('/tim-capstone/expo-project/edit-process', [ExpoProjectController::class, 'editExpoProjectProcess']);
    Route::get('/tim-capstone/expo-project/detail/{user_id}', [ExpoProjectController::class, 'detailExpoProject']);
    Route::get('/tim-capstone/expo-project/delete-process/{id}', [ExpoProjectController::class, 'deleteExpoProjectProcess']);

      // terima tolak expo
      Route::get('/tim-capstone/expo-project/terima/{id}', [ExpoProjectController::class, 'terimaKelompok']);
      Route::get('/tim-capstone/expo-project/tolak/{id}', [ExpoProjectController::class, 'tolakKelompok']);

      // hasil expo
      Route::get('/tim-capstone/expo-project/to-lulus/{id}', [ExpoProjectController::class, 'toLulusExpo']);
      Route::get('/tim-capstone/expo-project/to-gagal/{id}', [ExpoProjectController::class, 'toGagalExpo']);

      //sidang ta
      Route::get('/tim-capstone/sidang-ta', [SidangTAController::class, 'index']);
      Route::get('/tim-capstone/sidang-ta/add', [SidangTAController::class, 'addPeriodeSidangTA']);
      Route::post('/tim-capstone/sidang-ta/add-process', [SidangTAController::class, 'addPeriodeSidangTAProcess']);
      Route::get('/tim-capstone/sidang-ta/delete-process/{id}', [SidangTAController::class, 'deletePeriodeSidangTAProcess']);
      Route::get('/tim-capstone/sidang-ta/edit/{id}', [SidangTAController::class, 'editPeriodeSidangTA']);
      Route::post('/tim-capstone/sidang-ta/edit-process', [SidangTAController::class, 'editPeriodeSidangTAProcess']);
      Route::get('/tim-capstone/sidang-ta/detail/{id}', [SidangTAController::class, 'detailPeriodeSidangTA']);
      Route::get('tim-capstone/sidang-ta/jadwalkan-sidang-ta/{id}/{id_periode}', [SidangTAController::class, 'detailPenjadwalanSidangTA']);

     //add delete dosen pembimbing
     Route::get('tim-capstone/sidang-ta/add-dosen-penguji', [SidangTAController::class, 'addDosenKelompok']);
     Route::get('tim-capstone/sidang-ta/delete-dosen-penguji/{id_dosen}/{id_kelompok}', [SidangTAController::class, 'deleteDosenKelompok']);
     // jadwalkan sidang proposal
     Route::post('tim-capstone/sidang-ta/add-jadwal-process', [SidangTAController::class, 'addJadwalProcess']);

     // add jadwal to mahasiswa
     Route::get('/tim-capstone/sidang-ta/add-jadwal-to-mahasiswa', [SidangTAController::class, 'addJadwalToMahasiswa']);

      Route::get('/tim-capstone/sidang-ta/terima/{id}', [SidangTAController::class, 'terimaMahasiswa']);
      Route::get('/tim-capstone/sidang-ta/tolak/{id}', [SidangTAController::class, 'tolakMahasiswa']);



    //sidang TA
    Route::get('/tim-capstone/jadwal-sidang-ta', [JadwalSidangTAController::class, 'index']);
    Route::get('/tim-capstone/jadwal-sidang-ta/delete-process/{id}', [JadwalSidangTAController::class, 'deleteJadwalSidangTAProcess']);
    Route::get('/tim-capstone/jadwal-sidang-ta/to-lulus/{id}', [JadwalSidangTAController::class, 'toLulusSidangTA']);
    Route::get('/tim-capstone/jadwal-sidang-ta/to-gagal/{id}', [JadwalSidangTAController::class, 'toGagalSidangTA']);
    Route::get('/tim-capstone/jadwal-sidang-ta/detail/{id}', [JadwalSidangTAController::class, 'detailJadwalSidangTA']);
    Route::get('/tim-capstone/jadwal-sidang-ta/filter-periode', [JadwalSidangTAController::class, 'filterPeriodeKelompok']);
    Route::get('/tim-capstone/jadwal-sidang-ta/search', [JadwalSidangTAController::class, 'search']);

});

// mahasiswa
Route::middleware(['auth', 'role:03'])->group(function () {

    // beranda
    Route::get('mahasiswa/beranda', [DashboardController::class, 'indexMahasiswa']);

    //mahasiswakelompok
    Route::get('/mahasiswa/kelompok', [MahasiswaKelompokController::class, 'index']);
    Route::post('/mahasiswa/kelompok/add-kelompok-process', [MahasiswaKelompokController::class, 'addKelompokProcess']);
    Route::post('/mahasiswa/kelompok/add-punya-kelompok-process', [MahasiswaKelompokController::class, 'addPunyaKelompokProcess']);
    Route::post('/mahasiswa/kelompok/edit-kelompok-process', [MahasiswaKelompokController::class, 'editKelompokProcess']);

    Route::post('/mahasiswa/kelompok/terima-kelompok', [MahasiswaKelompokController::class, 'terimaKelompok'])->name('kelompok.accept');
    Route::post('/mahasiswa/kelompok/tolak-kelompok', [MahasiswaKelompokController::class, 'tolakKelompok'])->name('kelompok.reject');
    // mahasiswa by id
    Route::get('/admin/mahasiswa/get-by-id/{user_id}', [MahasiswaController::class, 'getById']);

    //mahasiswaFile
    Route::get('/mahasiswa/dokumen', [DokumenMahasiswaController::class, 'index']);
    Route::post('/mahasiswa/dokumen/upload-makalah', [DokumenMahasiswaController::class, 'uploadMakalahProcess']);
    Route::post('/mahasiswa/dokumen/upload-laporan', [DokumenMahasiswaController::class, 'uploadLaporanProcess']);

    Route::post('/mahasiswa/dokumen/upload-c100', [DokumenMahasiswaController::class, 'uploadC100Process']);
    Route::post('/mahasiswa/dokumen/upload-c200', [DokumenMahasiswaController::class, 'uploadC200Process']);
    Route::post('/mahasiswa/dokumen/upload-c300', [DokumenMahasiswaController::class, 'uploadC300Process']);
    Route::post('/mahasiswa/dokumen/upload-c400', [DokumenMahasiswaController::class, 'uploadC400Process']);
    Route::post('/mahasiswa/dokumen/upload-c500', [DokumenMahasiswaController::class, 'uploadC500Process']);

    // sidang proposal
    Route::get('/mahasiswa/sidang-proposal', [MahasiswaSidangProposalController::class, 'index']);


    //mahasiswa Expo
    Route::get('/mahasiswa/expo', [MahasiswaExpoController::class, 'index']);
    Route::post('/mahasiswa/expo/expo-daftar', [MahasiswaExpoController::class, 'daftarExpo']);

    // sidang TA
    Route::get('/mahasiswa/tugas-akhir', [MahasiswaTugasAkhirController::class, 'index']);
    Route::post('/mahasiswa/tugas-akhir/tugas-akhir-daftar', [MahasiswaTugasAkhirController::class, 'daftarTA']);
});


// dosen
Route::middleware(['auth', 'role:04'])->group(function () {

     // beranda
     Route::get('dosen/beranda', [DashboardController::class, 'indexDosen']);
     Route::get('dosen/beranda/filter-siklus', [DashboardController::class, 'filterSiklusByDosen']);

     //halaman dosen
     Route::get('/dosen/kelompok-bimbingan', [KelompokBimbinganController::class, 'index']);
     Route::get('/dosen/kelompok-bimbingan/terima/{id}', [KelompokBimbinganController::class, 'terimaKelompokBimbingan']);
     Route::get('/dosen/kelompok-bimbingan/tolak/{id}', [KelompokBimbinganController::class, 'tolakKelompokBimbingan']);
     Route::get('/dosen/kelompok-bimbingan/detail/{id}', [KelompokBimbinganController::class, 'detailKelompokBimbingan']);
     Route::get('/dosen/kelompok-bimbingan/search', [KelompokBimbinganController::class, 'search']);
     Route::get('/dosen/kelompok-bimbingan/filter-status', [KelompokBimbinganController::class, 'getKelompokBimbinganFilterStatus']);

     // detail mahasiswa bimbingan saya
     Route::get('/dosen/kelompok-bimbingan/detail-mahasiswa/{user_id}', [KelompokBimbinganController::class, 'detailMahasiswa']);

     // mahasiswa bimbingan
     Route::get('/dosen/mahasiswa-bimbingan', [MahasiswaBimbinganController::class, 'index']);
     Route::get('/dosen/mahasiswa-bimbingan/search', [MahasiswaBimbinganController::class, 'search']);
     Route::get('/dosen/mahasiswa-bimbingan/filter-status', [MahasiswaBimbinganController::class, 'getMahasiswaBimbinganFilterStatus']);

     // detail mahasiswa bimbingan saya
     Route::get('/dosen/mahasiswa-bimbingan/detail-mahasiswa/{user_id}', [MahasiswaBimbinganController::class, 'detailMahasiswa']);


     // persetujuan c100
    Route::get('/dosen/persetujuan-c100', [PersetujuanC100Controller::class, 'index']);
    Route::get('/dosen/persetujuan-c100/terima/{id}', [PersetujuanC100Controller::class, 'terimaPersetujuanC100Saya']);
    Route::get('/dosen/persetujuan-c100/tolak/{id}', [PersetujuanC100Controller::class, 'tolakPersetujuanC100Saya']);
    Route::get('/dosen/persetujuan-c100/detail/{id}', [PersetujuanC100Controller::class, 'detailPersetujuanC100Saya']);
    Route::get('/dosen/persetujuan-c100/search', [PersetujuanC100Controller::class, 'search']);

    // persetujuan c200
    Route::get('/dosen/persetujuan-c200', [PersetujuanC200Controller::class, 'index']);
    Route::get('/dosen/persetujuan-c200/terima/{id}', [PersetujuanC200Controller::class, 'terimaPersetujuanC200Saya']);
    Route::get('/dosen/persetujuan-c200/tolak/{id}', [PersetujuanC200Controller::class, 'tolakPersetujuanC200Saya']);
    Route::get('/dosen/persetujuan-c200/detail/{id}', [PersetujuanC200Controller::class, 'detailPersetujuanC200Saya']);
    Route::get('/dosen/persetujuan-c200/search', [PersetujuanC200Controller::class, 'search']);

    // persetujuan c300
    Route::get('/dosen/persetujuan-c300', [PersetujuanC300Controller::class, 'index']);
    Route::get('/dosen/persetujuan-c300/terima/{id}', [PersetujuanC300Controller::class, 'terimaPersetujuanC300Saya']);
    Route::get('/dosen/persetujuan-c300/tolak/{id}', [PersetujuanC300Controller::class, 'tolakPersetujuanC300Saya']);
    Route::get('/dosen/persetujuan-c300/detail/{id}', [PersetujuanC300Controller::class, 'detailPersetujuanC300Saya']);
    Route::get('/dosen/persetujuan-c300/search', [PersetujuanC300Controller::class, 'search']);

    // persetujuan c400
    Route::get('/dosen/persetujuan-c400', [PersetujuanC400Controller::class, 'index']);
    Route::get('/dosen/persetujuan-c400/terima/{id}', [PersetujuanC400Controller::class, 'terimaPersetujuanC400Saya']);
    Route::get('/dosen/persetujuan-c400/tolak/{id}', [PersetujuanC400Controller::class, 'tolakPersetujuanC400Saya']);
    Route::get('/dosen/persetujuan-c400/detail/{id}', [PersetujuanC400Controller::class, 'detailPersetujuanC400Saya']);
    Route::get('/dosen/persetujuan-c400/search', [PersetujuanC400Controller::class, 'search']);

    // persetujuan c500
    Route::get('/dosen/persetujuan-c500', [PersetujuanC500Controller::class, 'index']);
    Route::get('/dosen/persetujuan-c500/terima/{id}', [PersetujuanC500Controller::class, 'terimaPersetujuanC500Saya']);
    Route::get('/dosen/persetujuan-c500/tolak/{id}', [PersetujuanC500Controller::class, 'tolakPersetujuanC500Saya']);
    Route::get('/dosen/persetujuan-c500/detail/{id}', [PersetujuanC500Controller::class, 'detailPersetujuanC500Saya']);
    Route::get('/dosen/persetujuan-c500/search', [PersetujuanC500Controller::class, 'search']);

    // persetujuan laporan
    Route::get('/dosen/persetujuan-lta', [PersetujuanLaporanTAController::class, 'index']);
    Route::get('/dosen/persetujuan-lta/terima/{id}', [PersetujuanLaporanTAController::class, 'terimaPersetujuanLaporanTASaya']);
    Route::get('/dosen/persetujuan-lta/tolak/{id}', [PersetujuanLaporanTAController::class, 'tolakPersetujuanLaporanTASaya']);
    Route::get('/dosen/persetujuan-lta/detail/{id}', [PersetujuanLaporanTAController::class, 'detailPersetujuanLaporanTASaya']);
    Route::get('/dosen/persetujuan-lta/search', [PersetujuanLaporanTAController::class, 'search']);

    // persetujuan makalah
    Route::get('/dosen/persetujuan-mta', [PersetujuanMakalahTAController::class, 'index']);
    Route::get('/dosen/persetujuan-mta/terima/{id}', [PersetujuanMakalahTAController::class, 'terimaPersetujuanMakalahTASaya']);
    Route::get('/dosen/persetujuan-mta/tolak/{id}', [PersetujuanMakalahTAController::class, 'tolakPersetujuanMakalahTASaya']);
    Route::get('/dosen/persetujuan-mta/detail/{id}', [PersetujuanMakalahTAController::class, 'detailPersetujuanMakalahTASaya']);
    Route::get('/dosen/persetujuan-mta/search', [PersetujuanMakalahTAController::class, 'search']);

     //pengujian saya
     Route::get('/dosen/pengujian-proposal', [PengujianProposalController::class, 'index']);
     Route::get('/dosen/pengujian-proposal/terima/{id}', [PengujianProposalController::class, 'terimaPengujianProposalSaya']);
     Route::get('/dosen/pengujian-proposal/tolak/{id}', [PengujianProposalController::class, 'tolakPengujianProposalSaya']);
     Route::get('/dosen/pengujian-proposal/detail/{id}', [PengujianProposalController::class, 'detailPengujianProposalSaya']);
     Route::get('/dosen/pengujian-proposal/search', [PengujianProposalController::class, 'search']);

     // detail mahasiswa bimbingan proposal
     Route::get('/dosen/pengujian-proposal/detail-mahasiswa/{user_id}', [PengujianProposalController::class, 'detailMahasiswa']);


    //pengujian saya
    Route::get('/dosen/pengujian-ta', [PengujianTAController::class, 'index']);
    Route::get('/dosen/pengujian-ta/terima/{id}', [PengujianTAController::class, 'terimaPengujianTASaya']);
    Route::get('/dosen/pengujian-ta/tolak/{id}', [PengujianTAController::class, 'tolakPengujianTASaya']);
    Route::get('/dosen/pengujian-ta/detail/{id}', [PengujianTAController::class, 'detailPengujianTASaya']);
    Route::get('/dosen/pengujian-ta/search', [PengujianTAController::class, 'search']);


});


Route::middleware(['auth'])->group(function () {

    // logout
    Route::get('/logout', [LogoutController::class, 'logout']);

    // Dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'index']);
    // --------------------------------------------------------------------------------------------

    // profil acccount
    Route::get('admin/settings/account', [AccountController::class, 'index']);
    Route::post('admin/settings/account/edit_process', [AccountController::class, 'editProcess']);
    Route::post('admin/settings/account/edit_password', [AccountController::class, 'editPassword']);

    Route::post('admin/settings/account/img_crop', [AccountController::class, 'ImgCrop'])->name('crop');

});
