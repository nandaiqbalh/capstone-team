<?php

namespace App\Http\Controllers\Dosen\PengujianTA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\PengujianTA\PengujianTAModel;
use Illuminate\Support\Facades\Hash;


class PengujianTAController extends BaseController
{
    public function index()
    {
        // Get data with pagination
        $rs_pengujian_ta = PengujianTAModel::getDataWithPagination();

        foreach ($rs_pengujian_ta as $pengujian_ta) {
            if ($pengujian_ta->id_dosen_penguji_ta1 == Auth::user()->user_id) {
                $pengujian_ta->jenis_dosen = 'Penguji 1';
                $pengujian_ta->status_dosen = $pengujian_ta->status_dosen_penguji_ta1;
            } elseif ($pengujian_ta->id_dosen_penguji_ta2 == Auth::user()->user_id) {
                $pengujian_ta->jenis_dosen = 'Penguji 2';
                $pengujian_ta->status_dosen = $pengujian_ta->status_dosen_penguji_ta2;
            } else {
                $pengujian_ta->jenis_dosen = 'Belum Diplot';
                $pengujian_ta->status_dosen = 'Belum Diplot';
            }

            // Set status colors based on respective statuses
            $pengujian_ta->status_penguji1_color = $this->getStatusColor($pengujian_ta->status_dosen_penguji_ta1);
            $pengujian_ta->status_penguji2_color = $this->getStatusColor($pengujian_ta->status_dosen_penguji_ta2);
            // $pengujian_ta->status_pembimbing1_color = $this->getStatusColor($pengujian_ta->status_dosen_pembimbing_1);
            // $pengujian_ta->status_pembimbing2_color = $this->getStatusColor($pengujian_ta->status_dosen_pembimbing_2);

            // Format date and time
            $waktuSidang = strtotime($pengujian_ta->waktu);
            $pengujian_ta->hari_sidang = strftime('%A', $waktuSidang);
            $pengujian_ta->hari_sidang = $this->convertDayToIndonesian($pengujian_ta->hari_sidang);
            $pengujian_ta->tanggal_sidang = date('d-m-Y', $waktuSidang);
            $pengujian_ta->waktu_sidang = date('H:i:s', $waktuSidang);

            $waktuSelesai = strtotime($pengujian_ta->waktu_selesai);
            $pengujian_ta->waktu_selesai = date('H:i:s', $waktuSelesai);
        }

        // Data
        $data = ['rs_pengujian_ta' => $rs_pengujian_ta];

        // View
        return view('dosen.pengujian-ta.index', $data);
    }

    public function detailPengujianTASaya($id)
    {
       // get data with pagination
       $mahasiswa = PengujianTAModel::getDataDetailMahasiswaSidang($id);
       $rs_dosbing = PengujianTAModel::getAkunDosbingKelompok($mahasiswa->id_kelompok);
       $rs_penguji_ta = PengujianTAModel::getAkunPengujiTAKelompok($id);
       $rs_mahasiswa = PengujianTAModel::listMahasiswaSendiri($id, $mahasiswa->id_kelompok);

       // get jadwal sidang
       $jadwal_sidang = PengujianTAModel::getJadwalSidangTA($id);
       if($jadwal_sidang != null){
           $waktuSidang = strtotime($jadwal_sidang->waktu);

           $jadwal_sidang->hari_sidang = strftime('%A', $waktuSidang);
           $jadwal_sidang->hari_sidang = $this->convertDayToIndonesian($jadwal_sidang->hari_sidang);
           $jadwal_sidang->tanggal_sidang = date('d-m-Y', $waktuSidang);
           $jadwal_sidang->waktu_sidang = date('H:i:s', $waktuSidang);

           $waktuSelesai = strtotime($jadwal_sidang->waktu_selesai);
           $jadwal_sidang->waktu_selesai = date('H:i:s', $waktuSelesai);

       }

       $rs_ruang_sidang = PengujianTAModel::getRuangSidang();

       foreach ($rs_dosbing as $dosbing) {

           if ($dosbing->user_id == $mahasiswa->id_dosen_pembimbing_1) {
               $dosbing->jenis_dosen = 'Pembimbing 1';
               $dosbing->status_dosen = $mahasiswa->status_dosen_pembimbing_1;
           } else if ($dosbing->user_id == $mahasiswa->id_dosen_pembimbing_2) {
               $dosbing->jenis_dosen = 'Pembimbing 2';
               $dosbing->status_dosen = $mahasiswa->status_dosen_pembimbing_2;
           }

           $dosbing -> status_pembimbing1_color = $this->getStatusColor($mahasiswa->status_dosen_pembimbing_1);
           $dosbing -> status_pembimbing2_color = $this->getStatusColor($mahasiswa->status_dosen_pembimbing_2);

       }

       foreach ($rs_penguji_ta as $penguji_ta) {
           if ($penguji_ta->user_id == $mahasiswa->id_dosen_penguji_ta1) {
               $penguji_ta->jenis_dosen = 'Penguji 1';
               $penguji_ta->status_dosen = $mahasiswa->status_dosen_penguji_ta1;
           } else if ($penguji_ta->user_id == $mahasiswa->id_dosen_penguji_ta2) {
               $penguji_ta->jenis_dosen = 'Penguji 2';
               $penguji_ta->status_dosen = $mahasiswa->status_dosen_penguji_ta2;
           }
       }

       // check
       if (empty($mahasiswa)) {
           // flash message
           session()->flash('danger', 'Data tidak ditemukan.');
           return redirect('/admin/pengujian-ta');
       }

       $mahasiswa -> status_kelompok_color = $this->getStatusColor($mahasiswa->status_kelompok);
       $mahasiswa -> status_dokumen_color = $this->getStatusColor($mahasiswa->file_status_c100);
       $mahasiswa -> status_sidang_color = $this->getStatusColor($mahasiswa->status_tugas_akhir);

       $mahasiswa -> status_penguji1_color = $this->getStatusColor($mahasiswa->status_dosen_penguji_ta1);
       $mahasiswa -> status_penguji2_color = $this->getStatusColor($mahasiswa->status_dosen_penguji_ta2);

       // data
       $data = [
           'mahasiswa' => $mahasiswa,
           'rs_dosbing' => $rs_dosbing,
           'rs_mahasiswa' => $rs_mahasiswa,
           'rs_penguji_ta' => $rs_penguji_ta,
           'rs_ruang_sidang' => $rs_ruang_sidang,
           'jadwal_sidang' => $jadwal_sidang,

        ];
        // view
        return view('dosen.pengujian-ta.detail-mahasiswa', $data);
    }

    public function tolakPengujianTASaya(Request $request, $id)
    {
        // Get data pengujian TA
        $rs_pengujian_ta = PengujianTAModel::getDataWithPagination();
        $params = [];

        // Iterate over pengujian TA data
        foreach ($rs_pengujian_ta as $pengujian_ta) {
            if ($pengujian_ta->id_mahasiswa == $id) {
                if ($pengujian_ta->id_dosen_penguji_ta1 == Auth::user()->user_id) {
                    $params = ['status_dosen_penguji_ta1' => 'Penguji Tidak Setuju!'];
                    break;
                } elseif ($pengujian_ta->id_dosen_penguji_ta2 == Auth::user()->user_id) {
                    $params = ['status_dosen_penguji_ta2' => 'Penguji Tidak Setuju!'];
                    break;
                }
            }
        }

        // Check if $params is still empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'Tidak ditemukan kondisi yang cocok untuk pengguna.');
            return redirect('/dosen/pengujian-ta');
        }

        // Get kelompok data
        $kelompok = PengujianTAModel::pengecekan_kelompok_mahasiswa($id);



        // Get data pendaftaran sidang TA untuk mahasiswa dalam kelompok
        $rs_anggota_kelompok = PengujianTAModel::getAnggotaKelompok($kelompok->id);

        foreach ($rs_anggota_kelompok as $anggota_kelompok) {
            if ($anggota_kelompok->is_mendaftar_sidang != 0 &&
                $anggota_kelompok->status_tugas_akhir != "Menunggu Persetujuan Pendaftaran Sidang!" &&
                $anggota_kelompok->status_tugas_akhir != "Pendaftaran Sidang Tidak Disetujui!") {

                    // Get data pendaftaran sidang TA untuk mahasiswa saat ini
                    $rs_periodeSekarang = PengujianTAModel::getDataPendaftaranSidangTA($id);
                    $rs_periodeLalu = PengujianTAModel::getDataPendaftaranSidangTA($anggota_kelompok->id_mahasiswa);

                    foreach ($rs_periodeLalu as $periodeLalu) {
                        foreach ($rs_periodeSekarang as $periodeSekarang) {
                            // Process update based on periode
                            if ($periodeSekarang->id_periode == $periodeLalu->id_periode) {
                                // Update status for each mahasiswa in kelompok
                                if (PengujianTAModel::updateKelompokMhs($anggota_kelompok->id_mahasiswa, $params)) {
                                    $pengujian_ta_updated = PengujianTAModel::getDataById($id);

                                    // Check updated status for pengujian TA
                                    if ($pengujian_ta_updated->status_dosen_penguji_ta1 == "Penguji Tidak Setuju!" &&
                                        $pengujian_ta_updated->status_dosen_penguji_ta2 == "Penguji Tidak Setuju!") {
                                        $paramsUpdated = ['status_tugas_akhir' => 'Penguji Tidak Setuju!'];
                                    } else {
                                        $paramsUpdated = ['status_tugas_akhir' => 'Menunggu Persetujuan Penguji!'];
                                    }

                                    // Update status kelompok based on updated parameters
                                    PengujianTAModel::updateKelompokMhs($anggota_kelompok->id_mahasiswa, $paramsUpdated);
                                }
                            }
                            // Flash message for success
                            session()->flash('success', 'Data berhasil disimpan.');
                        }
                    }

            }
        }

        return redirect('/dosen/pengujian-ta');
    }

    public function terimaPengujianTASaya(Request $request, $id)
    {
        // Get data pengujian TA
        $rs_pengujian_ta = PengujianTAModel::getDataWithPagination();
        $params = [];

        // Iterate over pengujian TA data to find matching user and update parameters
        foreach ($rs_pengujian_ta as $pengujian_ta) {
            if ($pengujian_ta->id_mahasiswa == $id) {
                if ($pengujian_ta->id_dosen_penguji_ta1 == Auth::user()->user_id) {
                    $params = ['status_dosen_penguji_ta1' => 'Penguji Setuju!'];
                    break;
                } elseif ($pengujian_ta->id_dosen_penguji_ta2 == Auth::user()->user_id) {
                    $params = ['status_dosen_penguji_ta2' => 'Penguji Setuju!'];
                    break;
                }
            }
        }

        // Check if $params is empty, indicating no matching condition was met
        if (empty($params)) {
            session()->flash('danger', 'Tidak ditemukan kondisi yang cocok untuk pengguna.');
            return redirect('/dosen/pengujian-ta');
        }

        // Get kelompok data
        $kelompok = PengujianTAModel::pengecekan_kelompok_mahasiswa($id);

        // Get all members of the kelompok
        $rs_anggota_kelompok = PengujianTAModel::getAnggotaKelompok($kelompok->id);

        foreach ($rs_anggota_kelompok as $anggota_kelompok) {
            if ($anggota_kelompok->is_mendaftar_sidang != 0 &&
                $anggota_kelompok->status_tugas_akhir != "Menunggu Persetujuan Pendaftaran Sidang!" &&
                $anggota_kelompok->status_tugas_akhir != "Pendaftaran Sidang Tidak Disetujui!") {

                $rs_periodeSekarang = PengujianTAModel::getDataPendaftaranSidangTA($id);
                $rs_periodeLalu = PengujianTAModel::getDataPendaftaranSidangTA($anggota_kelompok->id_mahasiswa);

                foreach ($rs_periodeLalu as $periodeLalu) {
                    foreach ($rs_periodeSekarang as $periodeSekarang) {
                         // Check if the periode matches between current mahasiswa and member mahasiswa
                        if ($periodeSekarang->id_periode == $periodeLalu->id_periode) {
                            $paramsUpdated = [];

                            // Update status for each mahasiswa in kelompok
                            if (PengujianTAModel::updateKelompokMhs($anggota_kelompok->id_mahasiswa, $params)) {
                                $pengujian_ta_updated = PengujianTAModel::getDataById($anggota_kelompok->id_mahasiswa);

                                // Determine updated status for pengujian TA
                                if ($pengujian_ta_updated->status_dosen_penguji_ta1 == "Penguji Setuju!" &&
                                    $pengujian_ta_updated->status_dosen_penguji_ta2 == "Penguji Setuju!") {
                                    $paramsUpdated = ['status_individu' => 'Telah Dijadwalkan Sidang TA!', 'status_tugas_akhir' => 'Telah Dijadwalkan Sidang TA!'];
                                } else {
                                    $paramsUpdated = ['status_tugas_akhir' => 'Menunggu Persetujuan Penguji!'];
                                }

                                // Update status kelompok based on updated parameters
                                PengujianTAModel::updateKelompokMhs($anggota_kelompok->id_mahasiswa, $paramsUpdated);
                            }

                            // Flash message for success
                            session()->flash('success', 'Data berhasil disimpan.');
                        } else {
                            // Process update for current mahasiswa if periode does not match
                            if (PengujianTAModel::updateKelompokMhs($id, $params)) {
                                $paramsUpdated = [];

                                $pengujian_ta_updated = PengujianTAModel::getDataById($id);

                                // Determine updated status for pengujian TA
                                if ($pengujian_ta_updated->status_dosen_penguji_ta1 == "Penguji Setuju!" &&
                                    $pengujian_ta_updated->status_dosen_penguji_ta2 == "Penguji Setuju!") {
                                    $paramsUpdated = ['status_individu' => 'Telah Dijadwalkan Sidang TA!', 'status_tugas_akhir' => 'Telah Dijadwalkan Sidang TA!'];
                                } else {
                                    $paramsUpdated = ['status_tugas_akhir' => 'Menunggu Persetujuan Penguji!'];
                                }

                                // Update status kelompok based on updated parameters
                                PengujianTAModel::updateKelompokMhs($id, $paramsUpdated);
                            }

                            // Flash message for success
                            session()->flash('success', 'Data berhasil disimpan.');
                        }
                    }
                }

            }
        }

        return redirect('/dosen/pengujian-ta');
    }


    public function detailMahasiswa($user_id)
    {

        // get data with pagination
        $mahasiswa = PengujianTAModel::getDataMahasiswaById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }
        $rs_peminatan = PengujianTAModel::peminatanMahasiswa($user_id);

        foreach ($rs_peminatan as $key => $peminatan) {
            if ($peminatan->id == $mahasiswa->id_peminatan_individu1) {
                $peminatan->prioritas = "Prioritas 1";
            } else if($peminatan->id == $mahasiswa->id_peminatan_individu2) {
                $peminatan->prioritas = "Prioritas 2";
            }else if($peminatan->id == $mahasiswa->id_peminatan_individu3) {
                $peminatan->prioritas = "Prioritas 3";
            }else if($peminatan->id == $mahasiswa->id_peminatan_individu4) {
                $peminatan->prioritas = "Prioritas 4";
            } else {
                $peminatan->prioritas = "Belum memilih";

            }
        }
        // dd($mahasiswa);
        // data
        $data = [
            'mahasiswa' => $mahasiswa,
            'rs_peminatan'=>$rs_peminatan
        ];

        // view
        return view('dosen.pengujian-ta.detail-mahasiswa', $data);
    }



    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_pengujian_ta = PengujianTAModel::getDataSearch($nama);

            foreach ($rs_pengujian_ta as $pengujian_ta) {
                if ($pengujian_ta->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $pengujian_ta->jenis_dosen = 'Pembimbing 2';
                    $pengujian_ta -> status_dosen = $pengujian_ta ->status_dosen_pembimbing_2;

                } else if ($pengujian_ta->id_dosen_penguji_ta1 == Auth::user()->user_id) {
                    $pengujian_ta->jenis_dosen = 'Penguji 1';
                    $pengujian_ta -> status_dosen = $pengujian_ta ->status_dosen_penguji_ta1;

                } else if ($pengujian_ta->id_dosen_penguji_ta2 == Auth::user()->user_id) {
                    $pengujian_ta->jenis_dosen = 'Penguji 2';
                    $pengujian_ta -> status_dosen = $pengujian_ta ->status_dosen_penguji_ta2;

                } else {
                    $pengujian_ta->jenis_dosen = 'Belum Diplot';
                    $pengujian_ta->status_dosen = 'Belum Diplot';
                }
                $pengujian_ta -> status_penguji1_color = $this->getStatusColor($pengujian_ta->status_dosen_penguji_ta1);
                $pengujian_ta -> status_penguji2_color = $this->getStatusColor($pengujian_ta->status_dosen_penguji_ta2);
                $pengujian_ta -> status_pembimbing1_color = $this->getStatusColor($pengujian_ta->status_dosen_pembimbing_1);
                $pengujian_ta -> status_pembimbing2_color = $this->getStatusColor($pengujian_ta->status_dosen_pembimbing_2);

            }


            foreach ($rs_pengujian_ta as $pengujian_ta) {
                if ($pengujian_ta != null) {
                    $waktuSidang = strtotime($pengujian_ta->waktu);

                    $pengujian_ta->hari_sidang = strftime('%A', $waktuSidang);
                    $pengujian_ta->hari_sidang = $this->convertDayToIndonesian($pengujian_ta->hari_sidang);
                    $pengujian_ta->tanggal_sidang = date('d-m-Y', $waktuSidang);
                    $pengujian_ta->waktu_sidang = date('H:i:s', $waktuSidang);

                    $waktuSelesai = strtotime($pengujian_ta->waktu_selesai);
                    $pengujian_ta->waktu_selesai = date('H:i:s', $waktuSelesai);
                }
            }
            // data
            $data = ['rs_pengujian_ta' => $rs_pengujian_ta, 'nama' => $nama];
            // view
            return view('dosen.pengujian-ta.index', $data);
        } else {
            return view('dosen/pengujian-ta', $data);
        }
    }
}
