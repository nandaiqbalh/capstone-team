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


        // get data kelompok
        $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);
        $rs_siklus = MahasiswaKelompokModel::getSiklusAktif();
        $periodePendaftaran = MahasiswaKelompokModel::getPeriodePendaftaranSiklus();

        if ($kelompok != null) {

            // dari tabel kelompok_mhs
            $akun_mahasiswa = MahasiswaKelompokModel::getAkunByID(Auth::user()->user_id);
            $siklusSudahPunyaKelompok = MahasiswaKelompokModel::checkApakahSiklusMasihAktif($akun_mahasiswa ->id_siklus);


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
                'periode_pendaftaran' => $periodePendaftaran
            ];

        }

        // view
        return view('mahasiswa.kelompok-mahasiswa.detail', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function addKelompokProcess(Request $request)
     {

         $user = MahasiswaKelompokModel::getAkunBelumPunyaKelompok(Auth::user() -> user_id);

         // Check if the user exists
         if ($user != null && $user->user_active == 1) {
             // validation params
             $requiredParams = ['angkatan', 'email', 'jenis_kelamin', 'ipk', 'sks', 'no_telp', 'id_siklus', 'peminatan', 'topik'];

foreach ($requiredParams as $param) {
    if (!$request->has($param) || empty($request->input($param))) {
        session()->flash('danger', "Parameter '$param' kosong atau belum diisi!");
        return back()->withInput();
    }

    // Lakukan validasi tambahan sesuai dengan parameter yang bersangkutan
    switch ($param) {
        case 'angkatan':
            if (!preg_match('/^\d{4}$/', $request->input($param))) {
                session()->flash('danger', "Format angkatan tidak valid!");
                return back()->withInput();
            }
            break;
        case 'email':
            if (!filter_var($request->input($param), FILTER_VALIDATE_EMAIL)) {
                session()->flash('danger', "Format email tidak valid!");
                return back()->withInput();
            }
            break;
        case 'nim':
            if (!preg_match('/^\d{14}$/', $request->input($param))) {
                session()->flash('danger', "Format NIM tidak valid!");
                return back()->withInput();
            }
            break;
        case 'ipk':
            if (!preg_match('/^\d+(\.\d{1,2})?$/', $request->input($param)) || $request->input($param) < 0 || $request->input($param) > 4) {
                session()->flash('danger', "Format IPK tidak valid!");
                return back()->withInput();
            }
            break;
        case 'sks':
            if (!preg_match('/^\d{1,3}$/', $request->input($param)) || $request->input($param) < 0 || $request->input($param) > 160) {
                session()->flash('danger', "Format SKS tidak valid!");
                return back()->withInput();
            }
            break;
        case 'no_telp':
            if (!preg_match('/^\d{10,15}$/', $request->input($param))) {
                session()->flash('danger', "Format nomor telepon tidak valid!");
                return back()->withInput();
            }
            break;
        // Tambahkan case untuk parameter lainnya sesuai kebutuhan
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
                         'status_individu' => 'Menunggu Penetapan Kelompok!',
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
                    session()->flash('success', 'Berhasil mendaftar capstone!');
                    return redirect('/mahasiswa/kelompok');
                 } else {
                     session()->flash('danger', 'Pengguna tidak ditemukan!');
                    return back()->withInput();

                 }
             } catch (\Exception $e) {
                 session()->flash('danger', 'Gagal mendaftar capstone!');
                return back()->withInput();

             }
         } else {
            session()->flash('danger', 'Pengguna tidak ditemukan!');
            return back()->withInput();

         }
     }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
                    session()->flash('danger', "Parameter '$param' kosong atau belum diisi!");
                    return back()->withInput();
                }

                // Lakukan validasi tambahan sesuai dengan parameter yang bersangkutan
                switch ($param) {
                    case 'judul_capstone':
                        if (strlen($request->input($param)) > 255) {
                            session()->flash('danger', "Judul Capstone terlalu panjang!");
                            return back()->withInput();
                        }
                        break;
                    case 'id_siklus':
                        break;
                    case 'id_topik':
                        break;
                    case 'dosbing_1':
                        break;
                    case 'angkatan1':
                    case 'angkatan2':
                    case 'angkatan3':
                        // Validasi angkatan (4 digit angka)
                        if (!preg_match('/^\d{4}$/', $request->input($param))) {
                            session()->flash('danger', "Format angkatan tidak valid!");
                            return back()->withInput();
                        }
                        break;
                    case 'email1':
                    case 'email2':
                    case 'email3':
                        // Validasi email
                        if (!filter_var($request->input($param), FILTER_VALIDATE_EMAIL)) {
                            session()->flash('danger', "Format email tidak valid!");
                            return back()->withInput();
                        }
                        break;
                    case 'jenis_kelamin1':
                    case 'jenis_kelamin2':
                    case 'jenis_kelamin3':
                        // Validasi jenis_kelamin (Misal: harus salah satu dari opsi yang diperbolehkan)
                        break;
                    case 'ipk1':
                    case 'ipk2':
                    case 'ipk3':
                        // Validasi IPK (0-4, maksimal 2 angka dibelakang koma)
                        if (!preg_match('/^\d+(\.\d{1,2})?$/', $request->input($param)) || $request->input($param) < 0 || $request->input($param) > 4) {
                            session()->flash('danger', "Format IPK tidak valid!");
                            return back()->withInput();
                        }
                        break;
                    case 'sks1':
                    case 'sks2':
                    case 'sks3':
                        // Validasi SKS (3 digit angka, 0-160)
                        if (!preg_match('/^\d{1,3}$/', $request->input($param)) || $request->input($param) < 0 || $request->input($param) > 160) {
                            session()->flash('danger', "Format SKS tidak valid!");
                            return back()->withInput();
                        }
                        break;
                    case 'no_telp1':
                    case 'no_telp2':
                    case 'no_telp3':
                        // Validasi nomor telepon (10-15 digit angka)
                        if (!preg_match('/^\d{10,15}$/', $request->input($param))) {
                            session()->flash('danger', "Format nomor telepon tidak valid!");
                            return back()->withInput();
                        }
                        break;
                    // Tambahkan case untuk parameter lainnya sesuai kebutuhan
                    default:
                        // Tidak ada validasi tambahan yang diperlukan
                        break;
                }
            }


            foreach ($requiredParams as $param) {
                if (!$request->has($param) || empty($request->input($param))) {
                    session()->flash('danger', "Parameter '$param' kosong atau belum diisi!");
                    return back()->withInput();
                }
            }

            if ($request->dosbing_1 == $request->dosbing_2) {
                session()->flash('danger', "Dosen pembimbing tidak boleh sama!");
                return back()->withInput();
            }

            if ($request->user_id1 == $request->user_id2 || $request->user_id1 == $request->user_id3 || $request->user_id2 == $request->user_id3) {
                session()->flash('danger', "Mahasiswa tidak boleh sama!");
                return back()->withInput();
            }

            try {

                if (MahasiswaKelompokModel::isAccountExist($request -> user_id2) && MahasiswaKelompokModel::isAccountExist($request ->user_id3)) {
                    // addKelompok
                    $params = [
                        "id_siklus" => $request->id_siklus,
                        "judul_capstone" => $request->judul_capstone,
                        "id_topik" => $request->id_topik,
                        "status_kelompok" => 'Menunggu Persetujuan Anggota!',
                        "id_dosen_pembimbing_1" => $request->dosbing_1,
                        "status_dosen_pembimbing_1" =>'Menunggu Persetujuan Dosbing!',
                        "id_dosen_pembimbing_2" => $request->dosbing_2,
                        "status_dosen_pembimbing_2" =>'Menunggu Persetujuan Dosbing!',
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
                            'status_individu' => 'Menyetujui Kelompok!',
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
                            'status_individu' => 'Didaftarkan!',
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
                            'status_individu' => 'Didaftarkan!',
                            'usulan_judul_capstone' => $request -> judul_capstone,
                            'id_topik_mhs' => $request->id_topik,
                            'created_by'   => $user->user_id,
                            'created_date'  => now()
                        ];
                        MahasiswaKelompokModel::insertKelompokMHS($params33);
                    }

                    session()->flash('success', "Berhasil mendaftar capstone!");
                } else {
                    session()->flash('danger', "Pastikan semua mahasiswa merupakan mahasiswa aktif!");
                }

            } catch (\Exception $e) {
                session()->flash('danger', "Gagal mendaftar capstone!");
            }
        } else {
            // User not found or api_token is null
            session()->flash('danger', "Pengguna tidak ditemukan!");
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
                session()->flash('success', 'Berhasil menolak kelompok!');

            } catch (\Exception $e) {
                // Simpan pesan kegagalan ke dalam session flash
                session()->flash('danger', 'Gagal menolak!');
            }
        } else {
            // Simpan pesan kegagalan pengguna tidak ditemukan ke dalam session flash
            session()->flash('danger', 'Pengguna tidak ditemukan!');
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
                    "status_individu" => "Menyetujui Kelompok!",
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
                        if ($mahasiswa->status_individu !== "Menyetujui Kelompok!") {
                            $semuaSetuju = false;
                            // Jika salah satu mahasiswa tidak setuju, Anda bisa langsung keluar dari loop
                            break;
                        }
                    }

                    // Jika semua mahasiswa setuju dengan kelompok, lakukan aksi
                    if ($semuaSetuju) {
                        $paramKelompok = [
                            "status_kelompok" => "Menunggu Persetujuan Dosbing!",
                        ];
                        $update_kelompok = MahasiswaKelompokModel::updateKelompok($kelompok ->id, $paramKelompok);
                    }
                }

                // Simpan pesan sukses ke dalam session flash
                session()->flash('success', 'Berhasil menyetujui kelompok!');

            } catch (\Exception $e) {
                // Simpan pesan kegagalan ke dalam session flash
                session()->flash('danger', 'Gagal menyetujui!');
            }
        } else {
            // Simpan pesan kegagalan pengguna tidak ditemukan ke dalam session flash
            session()->flash('danger', 'Pengguna tidak ditemukan!');
        }

        // Kembalikan pengguna ke halaman sebelumnya dengan session flash
        return back()->withInput();
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailMahasiswa($user_id)
    {

        // get data with pagination
        $mahasiswa = MahasiswaKelompokModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('mahasiswa.mahasiswa.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editMahasiswa($user_id)
    {

        // get data
        $mahasiswa = MahasiswaKelompokModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('mahasiswa.mahasiswa.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editMahasiswaProcess(Request $request)
    {


        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            "nim" => 'required',
            "angkatan" => 'required',
            "ipk" => 'required',
            "sks" => 'required',
            "alamat" => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'user_name' => $request->nama,
            "nomor_induk" => $request->nim,
            "angkatan" => $request->angkatan,
            "ipk" => $request->ipk,
            "sks" => $request->sks,
            "alamat" => $request->alamat,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => now()
        ];

        // process
        if (MahasiswaKelompokModel::update($request->user_id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/mahasiswa');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/mahasiswa/edit/' . $request->user_id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMahasiswaProcess($user_id)
    {

        // get data
        $mahasiswa = MahasiswaKelompokModel::getDataById($user_id);

        // if exist
        if (!empty($mahasiswa)) {
            // process
            if (MahasiswaKelompokModel::delete($user_id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/mahasiswa');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/settings/contoh-halaman');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/contoh-halaman');
        }
    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchMahasiswa(Request $request)
    {

        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_mahasiswa = MahasiswaKelompokModel::getDataSearch($user_name);
            // dd($rs_mahasiswa);
            // data
            $data = ['rs_mahasiswa' => $rs_mahasiswa, 'nama' => $user_name];
            // view
            return view('mahasiswa.mahasiswa.index', $data);
        } else {
            return redirect('/admin/mahasiswa');
        }
    }
}
