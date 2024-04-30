<?php

namespace App\Http\Controllers\Mahasiswa\Kelompok_Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Mahasiswa\Kelompok_Mahasiswa\MahasiswaKelompokModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDO;

class MahasiswaKelompokController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {

        $rs_topik = MahasiswaKelompokModel::getTopik();

        // get data kelompok
        $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);
        $rs_siklus = MahasiswaKelompokModel::getSiklusAktif();
        $periodePendaftaran = MahasiswaKelompokModel::getPeriodePendaftaranSiklus();
        $akun_mahasiswa = MahasiswaKelompokModel::getAkunByID(Auth::user()->user_id);

        if ($kelompok != null) {

            // dari tabel kelompok_mhs
            $siklusSudahPunyaKelompok = MahasiswaKelompokModel::checkApakahSiklusMasihAktif($akun_mahasiswa ->id_siklus);

            $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
            $kelompok -> status_dokumen_color = $this->getStatusColor($kelompok->file_status_c100);
            $kelompok -> status_sidang_color = $this->getStatusColor($kelompok->status_sidang_proposal);

            $kelompok -> status_penguji1_color = $this->getStatusColor($kelompok->status_dosen_penguji_1);
            $kelompok -> status_penguji2_color = $this->getStatusColor($kelompok->status_dosen_penguji_2);
            $kelompok -> status_pembimbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
            $kelompok -> status_pembimbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);


            $rs_mahasiswa = MahasiswaKelompokModel::listKelompokMahasiswa($kelompok->id_kelompok);
            $rs_dosbing = MahasiswaKelompokModel::getAkunDosbingKelompok($kelompok->id_kelompok);

            foreach ($rs_dosbing as $dosbing) {

                if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_1) {
                    $dosbing->jenis_dosen = 'Pembimbing 1';
                    $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_1;
                } else if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_2) {
                    $dosbing->jenis_dosen = 'Pembimbing 2';
                    $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_2;
                }
            }

            $data = [
                'kelompok'  => $kelompok,
                'rs_mahasiswa' => $rs_mahasiswa,
                'rs_topik' => $rs_topik,
                'rs_dosbing' => $rs_dosbing,
                'rs_siklus' => $rs_siklus,
                'siklus_sudah_punya_kelompok' => $siklusSudahPunyaKelompok,
                'akun_mahasiswa' => $akun_mahasiswa,
            ];

            // dd($data);
        } else {
            $getAkun = MahasiswaKelompokModel::getAkunBelumPunyaKelompok(Auth::user()->user_id);
            $rs_dosbing1 = MahasiswaKelompokModel::getDataDosbing1();
            $rs_dosbing2 = MahasiswaKelompokModel::getDataDosbing2();
            $rs_topik = MahasiswaKelompokModel::getTopik();
            $rs_mahasiswa = MahasiswaKelompokModel::getDataMahasiswaAvailable();

            // data
            $data = [
                'kelompok'  => $kelompok,
                'getAkun' => $getAkun,
                'rs_topik' => $rs_topik,
                'rs_siklus' => $rs_siklus,
                'rs_mahasiswa' => $rs_mahasiswa,
                'rs_dosbing1' => $rs_dosbing1,
                'rs_dosbing2' => $rs_dosbing2,
                'akun_mahasiswa' => $akun_mahasiswa,
                'periode_pendaftaran' => $periodePendaftaran
            ];

        }

        // view
        return view('mahasiswa.kelompok-mahasiswa.detail', $data);
    }

     public function addKelompokProcess(Request $request)
     {

         $user = MahasiswaKelompokModel::getAkunBelumPunyaKelompok(Auth::user() -> user_id);

         // Check if the user exists
         if ($user != null && $user->user_active == 1) {
             // validation params
             $requiredParams = ['angkatan', 'email', 'jenis_kelamin', 'ipk', 'sks', 'no_telp', 'id_siklus', 'peminatan', 'topik'];

             foreach ($requiredParams as $param) {
                if (!$request->has($param) || empty($request->input($param))) {
                    switch ($param) {
                        case 'judul_capstone':
                            session()->flash('danger', "Judul Capstone kosong atau belum diisi");
                            break;
                        case 'id_siklus':
                            session()->flash('danger', "Siklus kosong atau belum diisi");
                            break;
                        case 'angkatan':
                            session()->flash('danger', "Angkatan kosong atau belum diisi");
                            break;
                        case 'email':
                            session()->flash('danger', "Email kosong atau belum diisi");
                            break;
                        case 'nim':
                            session()->flash('danger', "NIM kosong atau belum diisi");
                            break;
                        case 'ipk':
                            session()->flash('danger', "IPK kosong atau belum diisi");
                            break;
                        case 'sks':
                            session()->flash('danger', "SKS kosong atau belum diisi");
                            break;
                        case 'no_telp':
                            session()->flash('danger', "Nomor telepon kosong atau belum diisi");
                            break;
                        default:
                            session()->flash('danger', "Parameter '$param' kosong atau belum diisi");
                            break;
                    }
                    return back()->withInput();
                }

                // Lakukan validasi tambahan sesuai dengan parameter yang bersangkutan
                switch ($param) {
                    case 'angkatan':
                        if (!preg_match('/^\d{4}$/', $request->input($param))) {
                            session()->flash('danger', "Format angkatan tidak valid");
                            return back()->withInput();
                        }
                        break;
                    case 'email':
                        if (!filter_var($request->input($param), FILTER_VALIDATE_EMAIL)) {
                            session()->flash('danger', "Format email tidak valid");
                            return back()->withInput();
                        }
                        break;
                    case 'nim':
                        if (!preg_match('/^\d{14}$/', $request->input($param))) {
                            session()->flash('danger', "Format NIM tidak valid");
                            return back()->withInput();
                        }
                        break;
                    case 'ipk':
                        $ipk = $request->input($param);

                        // Validasi format IPK menggunakan ekspresi regulernya
                        if (!preg_match('/^\d\.\d{2}$/', $ipk)) {
                            session()->flash('danger', "Format IPK tidak valid! Harus dalam format x.yz (misal: 3.87).");
                            return back()->withInput();
                        }

                        // Validasi range IPK (harus antara 0 hingga 4)
                        if ($ipk < 0 || $ipk > 4) {
                            session()->flash('danger', "IPK harus berada di antara 0 hingga 4.");
                            return back()->withInput();
                        }
                        break;
                    case 'sks':
                        $sks = $request->input($param);

                        // Validasi format SKS menggunakan ekspresi regulernya (1-3 digit angka)
                        if (!preg_match('/^\d{1,3}$/', $sks)) {
                            session()->flash('danger', "Format SKS tidak valid! Harus berupa angka dengan panjang 1-3 digit.");
                            return back()->withInput();
                        }

                        // Validasi range SKS (harus antara 0 hingga 160)
                        if ($sks < 0 || $sks > 160) {
                            session()->flash('danger', "SKS harus berada di antara 0 hingga 160.");
                            return back()->withInput();
                        }
                        break;
                    case 'email':
                        if (!filter_var($request->input($param), FILTER_VALIDATE_EMAIL)) {
                            session()->flash('danger', "Format email tidak valid");
                            return back()->withInput();
                        }
                        break;
                    case 'no_telp':
                        if (!preg_match('/^\d{10,14}$/', $request->input($param))) {
                            session()->flash('danger', "Format nomor telepon tidak valid");
                            return back()->withInput();
                        }
                        break;
                    case 'peminatan':
                        case 'topik':
                            $values = $request->input($param);

                            // Periksa apakah format peminatan atau topik sesuai (4 angka unik antara 1-4 dipisahkan koma)
                            if (!preg_match('/^[1-4](,[1-4]){3}$/', $values)) {
                                session()->flash('danger', "Format $param tidak valid! Harus terdiri dari 4 angka unik antara 1-4, dipisahkan koma.");
                                return back()->withInput();
                            }

                            // Ubah string menjadi array untuk memeriksa angka yang unik
                            $valuesArray = explode(',', $values);

                            // Periksa apakah semua angka adalah unik
                            if (count(array_unique($valuesArray)) !== 4) {
                                session()->flash('danger', "Angka pada $param harus unik");
                                return back()->withInput();
                            }

                            break;
                        default:
                        // Tidak ada validasi tambahan yang diperlukan
                        break;
                }
            }

             try {
                 // params
                 $params = [
                     "angkatan" => $request->angkatan,
                     "user_name" => Auth::user()->user_name,
                     "nomor_induk" => Auth::user()->nomor_induk,
                     "user_email" => $request->email,
                     "jenis_kelamin" => $request->jenis_kelamin,
                     "ipk" => $request->ipk,
                     "sks" => $request->sks,
                     'no_telp' => $request->no_telp,
                     'modified_by'   => Auth::user()->user_id,
                     'modified_date'  => now(),
                 ];

                 // process
                 $update_mahasiswa = MahasiswaKelompokModel::updateMahasiswa($user->user_id, $params);

                 if ($update_mahasiswa) {

                    $peminatanEntered = $request ->peminatan;
                    $peminatanArray = array_map('trim', explode(',', $peminatanEntered));

                    $peminatan1 = MahasiswaKelompokModel::getPeminatanById($peminatanArray[0]);
                    $peminatan2 = MahasiswaKelompokModel::getPeminatanById($peminatanArray[1]);
                    $peminatan3 = MahasiswaKelompokModel::getPeminatanById($peminatanArray[2]);
                    $peminatan4 = MahasiswaKelompokModel::getPeminatanById($peminatanArray[3]);

                    $topikEntered = $request ->topik;
                    $topikArray = array_map('trim', explode(',', $topikEntered));

                    $topik1 = MahasiswaKelompokModel::getTopikById($topikArray[0]);
                    $topik2 = MahasiswaKelompokModel::getTopikById($topikArray[1]);
                    $topik3 = MahasiswaKelompokModel::getTopikById($topikArray[2]);
                    $topik4 = MahasiswaKelompokModel::getTopikById($topikArray[3]);

                     // Insert kelompok mhs
                     $params2 = [
                         'usulan_judul_capstone' => $request->judul_capstone,
                         'id_siklus' => $request->id_siklus,
                         'id_mahasiswa' => $user->user_id,
                         'status_individu' => 'Menunggu Penetapan Kelompok',
                         'id_topik_individu1' => $topik1->id,
                         'id_topik_individu2' => $topik2->id,
                         'id_topik_individu3' => $topik3->id,
                         'id_topik_individu4' => $topik4->id,
                         'id_peminatan_individu1' => $peminatan1->id,
                         'id_peminatan_individu2' => $peminatan2->id,
                         'id_peminatan_individu3' => $peminatan3->id,
                         'id_peminatan_individu4' => $peminatan4->id,
                         'created_by'   => Auth::user()->user_id,
                         'created_date'  => now(),
                     ];

                     MahasiswaKelompokModel::insertKelompokMHS($params2);

                    // flash message
                    session()->flash('success', 'Berhasil mendaftar capstone');
                    return redirect('/mahasiswa/kelompok');
                 } else {
                     session()->flash('danger', 'Pengguna tidak ditemukan');
                    return back()->withInput();

                 }
             } catch (\Exception $e) {
                 session()->flash('danger', 'Gagal mendaftar capstone');
                return back()->withInput();

             }
         } else {
            session()->flash('danger', 'Pengguna tidak ditemukan');
            return back()->withInput();

         }
     }


    public function addPunyaKelompokProcess(Request $request)
    {

        $user = MahasiswaKelompokModel::getAkunBelumPunyaKelompok(Auth::user()->user_id);

        // Check if the user exists
        if ($user != null && $user->user_active == 1) {
            // validation params
            $requiredParams = [
                'judul_capstone', 'id_siklus', 'id_topik', 'dosbing_1', 'dosbing_2',
                'angkatan1', 'email1', 'jenis_kelamin1', 'ipk1', 'sks1', 'no_telp1',
                'user_id2', 'angkatan2', 'email2', 'jenis_kelamin2', 'ipk2', 'sks2', 'no_telp2',
                'user_id3', 'angkatan3', 'email3', 'jenis_kelamin3', 'ipk3', 'sks3', 'no_telp3',
            ];

            foreach ($requiredParams as $param) {
                if (!$request->has($param) || empty($request->input($param))) {
                    $paramName = ucfirst(str_replace('_', ' ', $param));
                    session()->flash('danger', "$paramName kosong atau belum diisi");
                    return back()->withInput();
                }

                // Lakukan validasi tambahan sesuai dengan parameter yang bersangkutan
                switch ($param) {
                    case 'judul_capstone':
                        if (strlen($request->input($param)) > 255) {
                            session()->flash('danger', "Judul Capstone terlalu panjang");
                            return back()->withInput();
                        }
                        break;
                    case 'id_siklus':
                        // Validasi id_siklus jika diperlukan
                        break;
                    case 'id_topik':
                        // Validasi id_topik jika diperlukan
                        break;
                    case 'dosbing_1':
                        // Validasi dosbing_1 jika diperlukan
                        break;
                    case 'angkatan1':
                    case 'angkatan2':
                    case 'angkatan3':
                        // Validasi angkatan (4 digit angka)
                        if (!preg_match('/^\d{4}$/', $request->input($param))) {
                            $paramName = ucfirst(str_replace('_', ' ', $param));
                            session()->flash('danger', "Format angkatan tidak valid untuk $paramName");
                            return back()->withInput();
                        }
                        break;
                    case 'email1':
                    case 'email2':
                    case 'email3':
                        // Validasi email
                        if (!filter_var($request->input($param), FILTER_VALIDATE_EMAIL)) {
                            $paramName = ucfirst(str_replace('_', ' ', $param));
                            session()->flash('danger', "Format email tidak valid untuk $paramName");
                            return back()->withInput();
                        }
                        break;
                    case 'jenis_kelamin1':
                    case 'jenis_kelamin2':
                    case 'jenis_kelamin3':
                        // Validasi jenis kelamin (Misal: harus salah satu dari opsi yang diperbolehkan)
                        break;
                    case 'ipk1':
                    case 'ipk2':
                    case 'ipk3':
                        $ipk = $request->input($param);

                        // Validasi format IPK menggunakan ekspresi regulernya
                        if (!preg_match('/^\d\.\d{2}$/', $ipk)) {
                            $paramName = ucfirst(str_replace('_', ' ', $param));
                            session()->flash('danger', "Format IPK tidak valid untuk $paramName! Harus dalam format x.yz (misal: 3.87).");
                            return back()->withInput();
                        }

                        // Validasi range IPK (harus antara 0 hingga 4)
                        if ($ipk < 0 || $ipk > 4) {
                            session()->flash('danger', "IPK harus berada di antara 0 hingga 4 untuk $paramName.");
                            return back()->withInput();
                        }
                        break;
                    case 'sks1':
                    case 'sks2':
                    case 'sks3':
                        $sks = $request->input($param);

                        // Validasi format SKS menggunakan ekspresi regulernya (1-3 digit angka)
                        if (!preg_match('/^\d{1,3}$/', $sks)) {
                            $paramName = ucfirst(str_replace('_', ' ', $param));
                            session()->flash('danger', "Format SKS tidak valid untuk $paramName! Harus berupa angka dengan panjang 1-3 digit.");
                            return back()->withInput();
                        }

                        // Validasi range SKS (harus antara 0 hingga 160)
                        if ($sks < 0 || $sks > 160) {
                            session()->flash('danger', "SKS harus berada di antara 0 hingga 160 untuk $paramName.");
                            return back()->withInput();
                        }
                        break;
                    case 'no_telp1':
                    case 'no_telp2':
                    case 'no_telp3':
                        // Validasi nomor telepon (10-14 digit angka)
                        if (!preg_match('/^\d{10,14}$/', $request->input($param))) {
                            session()->flash('danger', "Format nomor telepon tidak valid untuk $paramName");
                            return back()->withInput();
                        }
                        break;
                    // Tambahkan case untuk parameter lainnya sesuai kebutuhan
                    default:
                        // Tidak ada validasi tambahan yang diperlukan
                        break;
                }
            }

            // Validasi tambahan setelah iterasi
            if ($request->dosbing_1 == $request->dosbing_2) {
                session()->flash('danger', "Dosen pembimbing tidak boleh sama");
                return back()->withInput();
            }

            if ($request->user_id1 == $request->user_id2 || $request->user_id1 == $request->user_id3 || $request->user_id2 == $request->user_id3) {
                session()->flash('danger', "Mahasiswa tidak boleh sama");
                return back()->withInput();
            }

            try {

                if (MahasiswaKelompokModel::isAccountExist($request -> user_id2) && MahasiswaKelompokModel::isAccountExist($request ->user_id3)) {
                    // addKelompok
                    $params = [
                        "id_siklus" => $request->id_siklus,
                        "judul_capstone" => $request->judul_capstone,
                        "id_topik" => $request->id_topik,
                        "status_kelompok" => 'Menunggu Persetujuan Anggota',
                        "id_dosen_pembimbing_1" => $request->dosbing_1,
                        "status_dosen_pembimbing_1" =>'Menunggu Persetujuan Dosbing',
                        "id_dosen_pembimbing_2" => $request->dosbing_2,
                        "status_dosen_pembimbing_2" =>'Menunggu Persetujuan Dosbing',
                        'created_by' => $user->user_id,
                        'created_date' => now()
                    ];

                    MahasiswaKelompokModel::insertKelompok($params);
                    $id_kelompok = DB::getPdo()->lastInsertId();

                    // params mahasiswa 1
                    $params1 = [
                        "angkatan" => $request->angkatan1,
                        "ipk" => $request->ipk1,
                        "user_email" => $request->email1,
                        "jenis_kelamin" => $request->jenis_kelamin1,
                        "sks" => $request->sks1,
                        'no_telp' => $request->no_telp1,
                        'modified_by'   => $user->user_id,
                        'modified_date'  => now()
                    ];

                    // process
                    $update_mahasiswa1 = MahasiswaKelompokModel::updateMahasiswa($user->user_id, $params1);
                    if ($update_mahasiswa1) {
                        $params11 = [
                            "id_siklus" => $request->id_siklus,
                            'id_kelompok' => $id_kelompok,
                            'id_mahasiswa' => $user->user_id,
                            'status_individu' => 'Menyetujui Kelompok',
                            'usulan_judul_capstone' => $request -> judul_capstone,
                            'id_topik_mhs' => $request->id_topik,
                            'created_by'   => $user->user_id,
                            'created_date'  => now()
                        ];
                        MahasiswaKelompokModel::insertKelompokMHS($params11);
                    }

                    // params mahasiswa 2
                    $params2 = [
                        "angkatan" => $request->angkatan2,
                        "user_email" => $request->email2,
                        "jenis_kelamin" => $request->jenis_kelamin2,
                        "ipk" => $request->ipk2,
                        "sks" => $request->sks2,
                        'no_telp' => $request->no_telp2,
                        'modified_by'   => $user->user_id,
                        'modified_date'  => now()
                    ];

                    // process
                    $update_mahasiswa2 = MahasiswaKelompokModel::updateMahasiswa($request->user_id2, $params2);
                    if ($update_mahasiswa2) {
                        $params22 = [
                            "id_siklus" => $request->id_siklus,
                            'id_kelompok' => $id_kelompok,
                            'id_mahasiswa' => $request->user_id2,
                            'status_individu' => 'Didaftarkan',
                            'usulan_judul_capstone' => $request -> judul_capstone,
                            'id_topik_mhs' => $request->id_topik,
                            'created_by'   => $user->user_id,
                            'created_date'  => now()
                        ];
                        MahasiswaKelompokModel::insertKelompokMHS($params22);
                    }

                    // params mahasiswa 3
                    $params3 = [
                        "angkatan" => $request->angkatan3,
                        "user_email" => $request->email3,
                        "jenis_kelamin" => $request->jenis_kelamin3,
                        "ipk" => $request->ipk3,
                        "sks" => $request->sks3,
                        'no_telp' => $request->no_telp3,
                        'modified_by'   => $user->user_id,
                        'modified_date'  => now()
                    ];

                    // process
                    $update_mahasiswa3 = MahasiswaKelompokModel::updateMahasiswa($request->user_id3, $params3);
                    if ($update_mahasiswa3) {
                        $params33 = [
                            "id_siklus" => $request->id_siklus,
                            'id_kelompok' => $id_kelompok,
                            'id_mahasiswa' => $request->user_id3,
                            'status_individu' => 'Didaftarkan',
                            'usulan_judul_capstone' => $request -> judul_capstone,
                            'id_topik_mhs' => $request->id_topik,
                            'created_by'   => $user->user_id,
                            'created_date'  => now()
                        ];
                        MahasiswaKelompokModel::insertKelompokMHS($params33);
                    }

                    session()->flash('success', "Berhasil mendaftar capstone");
                } else {
                    session()->flash('danger', "Pastikan semua mahasiswa merupakan mahasiswa aktif");
                }

            } catch (\Exception $e) {
                session()->flash('danger', "Gagal mendaftar capstone");
            }
        } else {
            // User not found or api_token is null
            session()->flash('danger', "Pengguna tidak ditemukan");
        }

        return redirect('/mahasiswa/kelompok');
    }


    public function tolakKelompok(Request $request)
    {
        $user = MahasiswaKelompokModel::getAkunByID(Auth::user()->user_id);

        // Memeriksa apakah pengguna ada
        if ($user != null && $user->user_active == 1) {
            try {
                // Proses penolakan kelompok
                $update_kelompok_mhs = MahasiswaKelompokModel::deleteKelompokMhs($user->user_id);

                // Simpan pesan sukses ke dalam session flash
                session()->flash('success', 'Berhasil menolak kelompok');

            } catch (\Exception $e) {
                // Simpan pesan kegagalan ke dalam session flash
                session()->flash('danger', 'Gagal menolak');
            }
        } else {
            // Simpan pesan kegagalan pengguna tidak ditemukan ke dalam session flash
            session()->flash('danger', 'Pengguna tidak ditemukan');
        }

        // Kembalikan pengguna ke halaman sebelumnya dengan session flash
        return back()->withInput();
    }

    public function terimaKelompok(Request $request)
    {
        $user = MahasiswaKelompokModel::getAkunByID(Auth::user()->user_id);

        // Memeriksa apakah pengguna ada
        if ($user != null && $user->user_active == 1) {
            try {
                // parameter
                $params = [
                    "status_individu" => "Menyetujui Kelompok",
                ];

                // Proses penerimaan kelompok
                $update_kelompok_mhs = MahasiswaKelompokModel::updateKelompokMHS($user->user_id, $params);

                // Inisialisasi variabel untuk menyimpan status setuju semua atau tidak
            $semuaSetuju = true;

                if ($update_kelompok_mhs) {
                    $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa($user->user_id);
                    $rs_mahasiswa = MahasiswaKelompokModel::listKelompokMahasiswa($kelompok->id_kelompok);

                    foreach ($rs_mahasiswa as $key => $mahasiswa) {
                        // Jika status individu bukan "menyetujui kelompok", set variabel $semuaSetuju menjadi false
                        if ($mahasiswa->status_individu !== "Menyetujui Kelompok") {
                            $semuaSetuju = false;
                            // Jika salah satu mahasiswa tidak setuju, Anda bisa langsung keluar dari loop
                            break;
                        }
                    }

                    // Jika semua mahasiswa setuju dengan kelompok, lakukan aksi
                    if ($semuaSetuju) {
                        $paramKelompok = [
                            "status_kelompok" => "Menunggu Persetujuan Dosbing",
                        ];
                        $update_kelompok = MahasiswaKelompokModel::updateKelompok($kelompok ->id, $paramKelompok);
                    }
                }

                // Simpan pesan sukses ke dalam session flash
                session()->flash('success', 'Berhasil menyetujui kelompok');

            } catch (\Exception $e) {
                // Simpan pesan kegagalan ke dalam session flash
                session()->flash('danger', 'Gagal menyetujui');
            }
        } else {
            // Simpan pesan kegagalan pengguna tidak ditemukan ke dalam session flash
            session()->flash('danger', 'Pengguna tidak ditemukan');
        }

        // Kembalikan pengguna ke halaman sebelumnya dengan session flash
        return back()->withInput();
    }

    public function editKelompokProcess(Request $request)
    {

        // Validate & auto redirect when fail
        $rules = [
            'id' => 'required',
        ];

        $this->validate($request, $rules);

        // params
        $params = [
            "id_topik" => $request->topik,
            "judul_capstone" => $request->judul_capstone,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (MahasiswaKelompokModel::updateKelompok($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return back();
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back();
        }
    }

}
