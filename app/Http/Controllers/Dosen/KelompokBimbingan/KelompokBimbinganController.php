<?php

namespace App\Http\Controllers\Dosen\KelompokBimbingan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\KelompokBimbingan\KelompokBimbinganModel;
use Illuminate\Support\Facades\Hash;


class KelompokBimbinganController extends BaseController
{


    public function index()
    {
        // get data with pagination
        $rs_bimbingan_saya = KelompokBimbinganModel::getDataWithPagination();

        foreach ($rs_bimbingan_saya as $bimbingan) {
            if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                $bimbingan->jenis_dosen = 'Pembimbing 1';
                $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_1;

            } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                $bimbingan->jenis_dosen = 'Pembimbing 2';
                $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_2;

            } else {
                $bimbingan->jenis_dosen = 'Belum Diplot';
            }

            $bimbingan -> status_kelompok_color = $this->getStatusColor($bimbingan->status_kelompok);
        }
        // data
        $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya];
        // view
        return view('dosen.kelompok-bimbingan.index', $data);
    }

    public function detailKelompokBimbingan($id)
    {

        // get data with pagination
        $kelompok = KelompokBimbinganModel::getDataById($id);
        $rs_dosbing = KelompokBimbinganModel::getAkunDosbingKelompok($id);
        $rs_mahasiswa = KelompokBimbinganModel::getMahasiswa($kelompok->id);

        // check
        if (empty($kelompok)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/dosen/kelompok-bimbingan');
        }


        foreach ($rs_mahasiswa as $mahasiswa) {
            $mahasiswa -> status_mahasiswa_color = $this->getStatusColor($mahasiswa->status_individu);

        }

        foreach ($rs_dosbing as $dosbing) {

            if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_1) {
                $dosbing->jenis_dosen = 'Pembimbing 1';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_1;
            } else if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_2) {
                $dosbing->jenis_dosen = 'Pembimbing 2';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_2;
            }

        }

        $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
        $kelompok -> status_dosbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
        $kelompok -> status_dosbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);


        // data
        $data = [
            'kelompok' => $kelompok,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_dosbing' => $rs_dosbing,
        ];

        // view
        return view('dosen.kelompok-bimbingan.detail', $data);
    }

    public function terimaKelompokBimbingan(Request $request, $id)
    {

        $rs_bimbingan_saya = KelompokBimbinganModel::getDataWithPagination();

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_bimbingan_saya as $bimbingan) {
            if ($bimbingan ->id == $id) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'Dosbing Setuju!',
                    ];
                    break;
                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'Dosbing Setuju!',
                    ];
                    break;
                }
            }
        }

        // process
        if (KelompokBimbinganModel::update($id, $params)) {

            $paramsUpdated = [];
            $bimbingan_saya_updated = KelompokBimbinganModel::getDataById($id);

            if ($bimbingan_saya_updated->id == $id) {
                if ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Dosbing Setuju!" &&
                    $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Dosbing Setuju!") {
                    $status_kelompok = 'Menunggu Persetujuan Tim Capstone!';
                } elseif ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Dosbing Diplot Tim Capstone!" &&
                          $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Dosbing Setuju!") {
                    $status_kelompok = 'Menunggu Persetujuan Tim Capstone!';
                } elseif ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Dosbing Setuju!" &&
                          $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Dosbing Diplot Tim Capstone!") {
                    $status_kelompok = 'Menunggu Persetujuan Tim Capstone!';
                } elseif ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Dosbing Diplot Tim Capstone!" &&
                          $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Dosbing Diplot Tim Capstone!") {
                    $status_kelompok = 'Menunggu Persetujuan Tim Capstone!';
                } else {
                    $status_kelompok = 'Menunggu Persetujuan Dosbing!';
                }

                KelompokBimbinganModel::update($id, ['status_kelompok' => $status_kelompok]);

            }
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/kelompok-bimbingan');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/kelompok-bimbingan');
        }
    }


    public function tolakKelompokBimbingan(Request $request, $id)
    {

        $rs_bimbingan_saya = KelompokBimbinganModel::getDataWithPagination();

        $jenis_dosen = ""; // Move the declaration outside the loop

        foreach ($rs_bimbingan_saya as $bimbingan) {
            if ($bimbingan ->id == $id) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 1';
                    $params = [
                        'status_dosen_pembimbing_1' => 'Dosbing Tidak Setuju!',
                    ];
                    break;
                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $jenis_dosen = 'Pembimbing 2';
                    $params = [
                        'status_dosen_pembimbing_2' => 'Dosbing Tidak Setuju!',
                    ];
                    break;
                }

            }

        }

        // process
        if (KelompokBimbinganModel::update($id, $params)) {

            $paramsUpdated = [];
            $bimbingan_saya_updated = KelompokBimbinganModel::getDataById($id);

            if ($bimbingan_saya_updated->id == $id) {
                if ($bimbingan_saya_updated->status_dosen_pembimbing_1 == "Dosbing Tidak Setuju!" &&
                    $bimbingan_saya_updated->status_dosen_pembimbing_2 == "Dosbing Tidak Setuju!") {

                    $paramsUpdated = ['status_kelompok' => 'Dosbing Tidak Setuju!'];
                    // Update status kelompok
                    KelompokBimbinganModel::update($id, $paramsUpdated);
                } else {
                    $paramsUpdated = ['status_kelompok' => 'Menunggu Penetapan Dosbing!'];

                    KelompokBimbinganModel::update($id, $paramsUpdated);

                }

            }
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/kelompok-bimbingan');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/kelompok-bimbingan');
        }
    }

    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_bimbingan_saya = KelompokBimbinganModel::getDataSearch($nama);
            foreach ($rs_bimbingan_saya as $bimbingan) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 1';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_1;

                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 2';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_2;

                } else {
                    $bimbingan->jenis_dosen = 'Belum Diplot';
                }
            }
            // data
            $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya, 'nama' => $nama];
            // view
            return view('dosen.kelompok-bimbingan.index', $data);
        } else {
            return view('dosen/kelompok-bimbingan', $data);
        }
    }

    public function getKelompokBimbinganFilterStatus(Request $request)
    {
        // data request
        $status = $request->status;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_bimbingan_saya = KelompokBimbinganModel::getKelompokBimbinganStatus($status);
            foreach ($rs_bimbingan_saya as $bimbingan) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 1';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_1;

                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 2';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_2;

                } else {
                    $bimbingan->jenis_dosen = 'Belum Diplot';
                }
            }
            // data
            $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya, 'status' => $status];
            // view
            return view('dosen.kelompok-bimbingan.index', $data);
        } else {
            return view('dosen/kelompok-bimbingan', $data);
        }
    }

    public function detailMahasiswa($user_id)
    {

        // get data with pagination
        $mahasiswa = KelompokBimbinganModel::getDataMahasiswaBimbinganById($user_id);

        $mahasiswa -> status_individu_color = $this->getStatusColor($mahasiswa->status_individu);
        $mahasiswa -> status_kelompok_color = $this->getStatusColor($mahasiswa->status_kelompok);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }
        $rs_peminatan = KelompokBimbinganModel::peminatanMahasiswa($user_id);

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
        return view('dosen.mahasiswa-bimbingan.detail-mahasiswa', $data);
    }

    private function getStatusColor($statusKelompok)
    {
        // Daftar status dan kategori warna
        $statusCategories = [
            'merah' => [
                'Dosbing Tidak Setuju!',
                'Penguji Tidak Setuju!',
                'C100 Tidak Disetujui!',
                'C200 Tidak Disetujui!',
                'C300 Tidak Disetujui!',
                'C400 Tidak Disetujui!',
                'C500 Tidak Disetujui!',
                'Kelompok Tidak Disetujui Expo!',
                'Laporan TA Tidak Disetujui!',
                'Makalah TA Tidak Disetujui!',
                'Belum Mendaftar Sidang TA!',
                'Gagal Expo Project!'
            ],
            'orange' => [
                'Menunggu Penetapan Kelompok!',
                'Menunggu Persetujuan Dosbing!',
                'Menunggu Persetujuan Anggota!',
                'Didaftarkan!',
                'Menunggu Penetapan Dosbing!',
                'Menunggu Persetujuan Tim Capstone!',
                'Menunggu Persetujuan C100!',
                'Menunggu Persetujuan C200!',
                'Menunggu Persetujuan C300!',
                'Menunggu Persetujuan C400!',
                'Menunggu Persetujuan C500!',
                'Menunggu Persetujuan Expo!',
                'Menunggu Persetujuan Laporan TA!',
                'Menunggu Persetujuan Makalah TA!',
                'Menunggu Persetujuan Penguji!',
                'Menunggu Penjadwalan Sidang TA!'
            ],
            'ijo' => [
                'Menyetujui Kelompok!',
                'Dosbing Setuju!',
                'Kelompok Diplot Tim Capstone!',
                'Dosbing Diplot Tim Capstone!',
                'Kelompok Telah Disetujui!',
                'C100 Telah Disetujui!',
                'Penguji Setuju!',
                'Dijadwalkan Sidang Proposal!',
                'Lulus Sidang Proposal!',
                'C200 Telah Disetujui!',
                'C300 Telah Disetujui!',
                'C400 Telah Disetujui!',
                'C500 Telah Disetujui!',
                'Kelompok Disetujui Expo!',
                'Lulus Expo Project!',
                'Laporan TA Telah Disetujui!',
                'Makalah TA Telah Disetujui!',
                'Penguji TA Setuju!',
                'Telah Dijadwalkan Sidang TA!',
                'Lulus Sidang TA!'
            ]
        ];

        $color = '#FF0000'; // Default warna merah

        // Loop melalui daftar kategori warna dan status
        foreach ($statusCategories as $category => $statuses) {
            if (in_array($statusKelompok, $statuses)) {
                // Temukan status dalam kategori, tetapkan warna sesuai
                switch ($category) {
                    case 'orange':
                        $color = '#F86F03'; // Warna orange
                        break;
                    case 'ijo':
                        $color = '#44B158'; // Warna hijau
                        break;
                    // Tidak perlu menangani 'merah' karena sudah menjadi default
                }
                break; // Hentikan loop setelah menemukan kategori yang sesuai
            }
        }

        return $color;
    }
}
