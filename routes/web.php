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
use App\Http\Controllers\TimCapstone\Siklus\SiklusController;
use App\Http\Controllers\TimCapstone\Broadcast\BroadcastController;
use App\Http\Controllers\TimCapstone\SidangProposal\JadwalSidangProposal\JadwalSidangProposalController;
use App\Http\Controllers\TimCapstone\ExpoProject\ExpoProjectController;
use App\Http\Controllers\TimCapstone\Kelompok\KelompokValid\KelompokValidController;
use App\Http\Controllers\TimCapstone\Kelompok\PenetapanAnggota\PenetapanAnggotaController;
use App\Http\Controllers\TimCapstone\Kelompok\PenetapanDosbing\PenetapanDosbingController;
use App\Http\Controllers\TimCapstone\Kelompok\ValidasiKelompok\ValidasiKelompokController;
// sidang proposal
use App\Http\Controllers\TimCapstone\SidangProposal\PenjadwalanSidangProposal\PenjadwalanSidangProposalController;


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
use App\Http\Controllers\Dosen\PengujianProposal\PengujianProposalController;


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

     //topik
     Route::get('/admin/peminatan', [PeminatanController::class, 'index']);
     Route::get('/admin/peminatan/add', [PeminatanController::class, 'addPeminatan']);
     Route::post('/admin/peminatan/add-process', [PeminatanController::class, 'addPeminatanProcess']);
     Route::get('/admin/peminatan/delete-process/{id}', [PeminatanController::class, 'deletePeminatanProcess']);
     Route::get('/admin/peminatan/edit/{id}', [PeminatanController::class, 'editPeminatan']);
     Route::post('/admin/peminatan/edit-process', [PeminatanController::class, 'editPeminatanProcess']);

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

     // balancing dosen pembimbing
     Route::get('/admin/balancing-dosbing-kelompok', [PembimbingKelompokController::class, 'balancingDosbingKelompok']);
     Route::get('/admin/balancing-dosbing-kelompok/filter-siklus', [PembimbingKelompokController::class, 'filterBalancingDosbingKelompok']);
     Route::get('/admin/balancing-dosbing-kelompok/detail/{user_id}', [PembimbingKelompokController::class, 'detailBalancingDosbingKelompok']);
     Route::get('/admin/balancing-dosbing-kelompok/search', [PembimbingKelompokController::class, 'searchBalancingDosbingKelompok']);

     // balancing dosen pembimbing mahasiswa
     Route::get('/admin/balancing-dosbing-mahasiswa', [PembimbingMahasiswaController::class, 'balancingDosbingMahasiswa']);
     Route::get('/admin/balancing-dosbing-mahasiswa/filter-siklus', [PembimbingMahasiswaController::class, 'filterBalancingDosbingMahasiswa']);
     Route::get('/admin/balancing-dosbing-mahasiswa/detail/{user_id}', [PembimbingMahasiswaController::class, 'detailBalancingDosbingMahasiswa']);
     Route::get('/admin/balancing-dosbing-mahasiswa/search', [PembimbingMahasiswaController::class, 'searchBalancingDosbingMahasiswa']);
     Route::get('/admin/balancing-dosbing-mahasiswa/detail-mahasiswa/{user_id}', [PembimbingMahasiswaController::class, 'detailMahasiswa']);

    // balancing dosen penguji proposal
     Route::get('/admin/balancing-penguji-proposal', [PengujiProposalController::class, 'balancingPengujiProposal']);
     Route::get('/admin/balancing-penguji-proposal/filter-siklus', [PengujiProposalController::class, 'filterBalancingPengujiProposal']);
     Route::get('/admin/balancing-penguji-proposal/detail/{user_id}', [PengujiProposalController::class, 'detailBalancingPengujiProposal']);
     Route::get('/admin/balancing-penguji-proposal/search', [PengujiProposalController::class, 'searchBalancingPengujiProposal']);


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

     //expo
     Route::get('/admin/expo-project', [ExpoProjectController::class, 'index']);
     Route::get('/admin/expo-project/add', [ExpoProjectController::class, 'addExpoProject']);
     Route::post('/admin/expo-project/add-process', [ExpoProjectController::class, 'addExpoProjectProcess']);
     Route::get('/admin/expo-project/edit/{id}', [ExpoProjectController::class, 'editExpoProject']);
     Route::post('/admin/expo-project/edit-process', [ExpoProjectController::class, 'editExpoProjectProcess']);
     Route::get('/admin/expo-project/detail/{user_id}', [ExpoProjectController::class, 'detailExpoProject']);
     Route::get('/admin/expo-project/delete-process/{id}', [ExpoProjectController::class, 'deleteExpoProjectProcess']);

     // terima tolak expo
     Route::get('/admin/expo-project/terima/{id}', [ExpoProjectController::class, 'terimaKelompok']);
     Route::get('/admin/expo-project/tolak/{id}', [ExpoProjectController::class, 'tolakKelompok']);

     // hasil expo
     Route::get('/admin/expo-project/to-lulus/{id}', [ExpoProjectController::class, 'toLulusExpo']);
     Route::get('/admin/expo-project/to-gagal/{id}', [ExpoProjectController::class, 'toGagalExpo']);

     Route::get('/admin/jadwal-pendaftaran/sidangta/terima/{id}', [JadwalSidangTAController::class, 'terimaKelompok']);
     Route::get('/admin/jadwal-pendaftaran/sidangta/tolak/{id}', [JadwalSidangTAController::class, 'tolakKelompok']);

     // penetapan kelompok
     Route::get('/admin/penetapan-anggota', [PenetapanAnggotaController::class, 'index']);
     Route::get('/admin/penetapan-anggota/add', [PenetapanAnggotaController::class, 'addPenetapanAnggota']);
     Route::get('/admin/penetapan-anggota/search', [PenetapanAnggotaController::class, 'searchMahasiswa']);
     Route::post('/admin/penetapan-anggota/add-process', [PenetapanAnggotaController::class, 'addPenetapanAnggotaProcess']);

    // penetapan dosbing kelompok
    Route::get('/admin/penetapan-dosbing', [PenetapanDosbingController::class, 'index']);
    Route::get('/admin/penetapan-dosbing/detail/{id}', [PenetapanDosbingController::class, 'detailKelompok']);
    //add delete dosen pembimbing
    Route::get('/admin/penetapan-dosbing/add-dosen-kelompok', [PenetapanDosbingController::class, 'addDosenKelompok']);
    Route::get('/admin/penetapan-dosbing/delete-dosen-process/{id_dosen}/{id_kelompok}', [PenetapanDosbingController::class, 'deleteDosenKelompok']);

    // validasi kelompok
    Route::get('/admin/validasi-kelompok', [ValidasiKelompokController::class, 'index']);
    Route::get('/admin/validasi-kelompok/detail/{id}', [ValidasiKelompokController::class, 'detailKelompok']);
    // edit dan delete kelompok
    Route::post('/admin/validasi-kelompok/setujui-kelompok-process', [ValidasiKelompokController::class, 'setujuiKelompokProcess']);
    Route::post('/admin/validasi-kelompok/edit-kelompok-process', [ValidasiKelompokController::class, 'editKelompokProcess']);
    Route::get('/admin/validasi-kelompok/delete-process/{id}', [ValidasiKelompokController::class, 'deleteKelompokProcess']);
    // add/delete dosen & mahasiswa
    Route::get('/admin/validasi-kelompok/add-mahasiswa-kelompok', [ValidasiKelompokController::class, 'addMahasiswaKelompok']);
    Route::get('/admin/validasi-kelompok/add-dosen-kelompok', [ValidasiKelompokController::class, 'addDosenKelompok']);
    Route::get('/admin/validasi-kelompok/delete-mahasiswa-process/{id_mahasiswa}/{id}', [ValidasiKelompokController::class, 'deleteMahasiswaKelompokProcess']);
    Route::get('/admin/validasi-kelompok/delete-dosen-process/{id_dosen}/{id}', [ValidasiKelompokController::class, 'deleteDosenKelompokProcess']);

    //kelompok valid
    Route::get('/admin/kelompok-valid', [KelompokValidController::class, 'index']);
    Route::get('/admin/kelompok-valid/search', [KelompokValidController::class, 'search']);
    Route::get('/admin/kelompok-valid/delete-process/{id}', [KelompokValidController::class, 'deleteKelompokProcess']);
    Route::get('/admin/kelompok-valid/detail/{id}', [KelompokValidController::class, 'detailKelompok']);


    // add sidang proposal
    Route::get('admin/penjadwalan-sidang-proposal', [PenjadwalanSidangProposalController::class, 'index']);
    Route::get('admin/penjadwalan-sidang-proposal/jadwalkan-sidang-proposal/{id}', [PenjadwalanSidangProposalController::class, 'detailKelompok']);
    //add delete dosen pembimbing
    Route::get('admin/penjadwalan-sidang-proposal/add-dosen-penguji', [PenjadwalanSidangProposalController::class, 'addDosenKelompok']);
    Route::get('admin/penjadwalan-sidang-proposal/delete-dosen-penguji/{id_dosen}/{id_kelompok}', [PenjadwalanSidangProposalController::class, 'deleteDosenKelompok']);
    // jadwalkan sidang proposal
    Route::post('admin/penjadwalan-sidang-proposal/add-jadwal-process', [PenjadwalanSidangProposalController::class, 'addJadwalProcess']);

    //sidang proposal
    Route::get('/admin/jadwal-sidang-proposal', [JadwalSidangProposalController::class, 'index']);
    Route::get('/admin/jadwal-sidang-proposal/delete-process/{id}', [JadwalSidangProposalController::class, 'deleteJadwalSidangProposalProcess']);
    Route::get('/admin/jadwal-sidang-proposal/to-lulus/{id}', [JadwalSidangProposalController::class, 'toLulusSidangProposal']);
    Route::get('/admin/jadwal-sidang-proposal/to-gagal/{id}', [JadwalSidangProposalController::class, 'toGagalSidangProposal']);

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
    //pengujian saya
    Route::get('/dosen/persetujuan-c100', [PersetujuanC100Controller::class, 'index']);
    Route::get('/dosen/persetujuan-c100/terima/{id}', [PersetujuanC100Controller::class, 'terimaPersetujuanC100Saya']);
    Route::get('/dosen/persetujuan-c100/tolak/{id}', [PersetujuanC100Controller::class, 'tolakPersetujuanC100Saya']);
    Route::get('/dosen/persetujuan-c100/detail/{id}', [PersetujuanC100Controller::class, 'detailPersetujuanC100Saya']);
    Route::get('/dosen/persetujuan-c100/search', [PersetujuanC100Controller::class, 'search']);

    // detail mahasiswa bimbingan proposal
    Route::get('/dosen/persetujuan-c100/detail-mahasiswa/{user_id}', [PersetujuanC100Controller::class, 'detailMahasiswa']);


     //pengujian saya
     Route::get('/dosen/pengujian-proposal', [PengujianProposalController::class, 'index']);
     Route::get('/dosen/pengujian-proposal/terima/{id}', [PengujianProposalController::class, 'terimaPengujianProposalSaya']);
     Route::get('/dosen/pengujian-proposal/tolak/{id}', [PengujianProposalController::class, 'tolakPengujianProposalSaya']);
     Route::get('/dosen/pengujian-proposal/detail/{id}', [PengujianProposalController::class, 'detailPengujianProposalSaya']);
     Route::get('/dosen/pengujian-proposal/search', [PengujianProposalController::class, 'search']);

     // detail mahasiswa bimbingan proposal
     Route::get('/dosen/pengujian-proposal/detail-mahasiswa/{user_id}', [PengujianProposalController::class, 'detailMahasiswa']);


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
