<?php

namespace App\Http\Controllers\Dosen\MahasiswaBimbingan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\MahasiswaBimbingan\MahasiswaBimbinganModel;
use Illuminate\Support\Facades\Hash;


class MahasiswaBimbinganController extends BaseController
{


    public function index()
    {
        // get data with pagination
        $rs_bimbingan_saya = MahasiswaBimbinganModel::getDataWithPagination();

        foreach ($rs_bimbingan_saya as $bimbingan) {
            if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                $bimbingan->jenis_dosen = 'Pembimbing 1';
                $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_1;

            } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                $bimbingan->jenis_dosen = 'Pembimbing 2';
                $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_2;

            } else {
                $bimbingan->jenis_dosen = 'Belum diplot';
            }

            $bimbingan -> status_color = $this->getStatusColor($bimbingan->status_individu);

        }
        // data
        $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya];
        // view
        return view('dosen.mahasiswa-bimbingan.index', $data);
    }

    public function detailMahasiswa($user_id)
    {

        // get data with pagination
        $mahasiswa = MahasiswaBimbinganModel::getDataMahasiswaBimbinganById($user_id);

        $mahasiswa -> status_individu_color = $this->getStatusColor($mahasiswa->status_individu);
        $mahasiswa -> status_kelompok_color = $this->getStatusColor($mahasiswa->status_kelompok);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }
        $rs_peminatan = MahasiswaBimbinganModel::peminatanMahasiswa($user_id);

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



    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_bimbingan_saya = MahasiswaBimbinganModel::getDataSearch($nama);
            foreach ($rs_bimbingan_saya as $bimbingan) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 1';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_1;

                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 2';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_2;

                } else {
                    $bimbingan->jenis_dosen = 'Belum diplot';
                }
            }
            // data
            $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya, 'nama' => $nama];
            // view
            return view('dosen.mahasiswa-bimbingan.index', $data);
        } else {
            return view('dosen/mahasiswa-bimbingan', $data);
        }
    }

    public function getMahasiswaBimbinganFilterStatus(Request $request)
    {
        // data request
        $status = $request->status;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_bimbingan_saya = MahasiswaBimbinganModel::getMahasiswaBimbinganStatus($status);
            foreach ($rs_bimbingan_saya as $bimbingan) {
                if ($bimbingan->id_dosen_pembimbing_1 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 1';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_1;

                } else if ($bimbingan->id_dosen_pembimbing_2 == Auth::user()->user_id) {
                    $bimbingan->jenis_dosen = 'Pembimbing 2';
                    $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_2;

                } else {
                    $bimbingan->jenis_dosen = 'Belum diplot';
                }
            }
            // data
            $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya, 'status' => $status];
            // view
            return view('dosen.mahasiswa-bimbingan.index', $data);
        } else {
            return view('dosen/mahasiswa-bimbingan', $data);
        }
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
                'C100 Menunggu Persetujuan Dosbing 1!',
                'C100 Menunggu Persetujuan Dosbing 2!',
                'C200 Menunggu Persetujuan Dosbing 1!',
                'C200 Menunggu Persetujuan Dosbing 2!',
                'C300 Menunggu Persetujuan Dosbing 1!',
                'C300 Menunggu Persetujuan Dosbing 2!',
                'C400 Menunggu Persetujuan Dosbing 1!',
                'C400 Menunggu Persetujuan Dosbing 2!',
                'C500 Menunggu Persetujuan Dosbing 1!',
                'C500 Menunggu Persetujuan Dosbing 2!',
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
