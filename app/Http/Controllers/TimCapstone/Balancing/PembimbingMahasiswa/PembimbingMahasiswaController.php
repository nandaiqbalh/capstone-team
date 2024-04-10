<?php

namespace App\Http\Controllers\TimCapstone\Balancing\PembimbingMahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Balancing\PembimbingMahasiswa\PembimbingMahasiswaModel;
use Illuminate\Support\Facades\Hash;


class PembimbingMahasiswaController extends BaseController
{
    public function balancingDosbingMahasiswa()
    {
        // get data with pagination
        $dt_dosen = PembimbingMahasiswaModel::getDataBalancingDosbingMahasiswa();
        $rs_siklus = PembimbingMahasiswaModel::getSiklusAktif();

        // data
        $data = [
            'dt_dosen' => $dt_dosen,
            'rs_siklus' => $rs_siklus,
        ];
        // view
        return view('tim_capstone.dosen.balancing.dosbing-mahasiswa.index', $data);
    }

    public function filterBalancingDosbingMahasiswa(Request $request)
    {
        // data request
        $id_siklus = $request->id_siklus;

        // new search or reset
        if ($request->action == 'search') {
            $dt_dosen = PembimbingMahasiswaModel::getDataBalancingDosbingMahasiswaFilterSiklus($id_siklus);
            $rs_siklus = PembimbingMahasiswaModel::getSiklusAktif();

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
            ];
            // view
            return view('tim_capstone.dosen.balancing.dosbing-mahasiswa.index', $data);
        } else {
            return redirect('/admin/balancing-dosbing-mahasiswa');
        }
    }

    public function detailBalancingDosbingMahasiswa($user_id)
    {
        // get data with pagination
        $rs_bimbingan = PembimbingMahasiswaModel::getDataBimbinganDosbingMahasiswa($user_id);

        foreach ($rs_bimbingan as $bimbingan) {
            $bimbingan -> status_individu_color = $this->getStatusColor($bimbingan->status_individu);
        }

        // data
        $data = ['rs_bimbingan' => $rs_bimbingan];
        // view
        return view('tim_capstone.dosen.balancing.dosbing-mahasiswa.detail', $data);
    }

    public function searchBalancingDosbingMahasiswa(Request $request)
    {
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $dt_dosen = PembimbingMahasiswaModel::searchBalancingDosbingMahasiswa($user_name);
            $rs_siklus = PembimbingMahasiswaModel::getSiklusAktif();

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
                'nama' => $user_name
            ];
            // view
            return view('tim_capstone.dosen.balancing.dosbing-mahasiswa.index', $data);
        } else {
            return redirect('/admin/dosen');
        }
    }

    public function detailMahasiswa($user_id)
    {

        // get data with pagination
        $mahasiswa = PembimbingMahasiswaModel::getDataMahasiswaBimbinganById($user_id);

        $mahasiswa -> status_individu_color = $this->getStatusColor($mahasiswa->status_individu);
        $mahasiswa -> status_kelompok_color = $this->getStatusColor($mahasiswa->status_kelompok);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }
        $rs_peminatan = PembimbingMahasiswaModel::peminatanMahasiswa($user_id);

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
