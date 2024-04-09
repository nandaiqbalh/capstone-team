<?php

namespace App\Http\Controllers\TimCapstone\Kelompok\KelompokValid;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Kelompok\KelompokValid\KelompokValidModel;
use App\Models\Mahasiswa\Kelompok_Mahasiswa\MahasiswaKelompokModel;
use Illuminate\Support\Facades\Hash;


class KelompokValidController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {

        // get data with pagination
        $rs_kelompok = KelompokValidModel::getDataWithPagination();

        foreach ($rs_kelompok as $kelompok) {

            $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
            $kelompok -> status_dosbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
            $kelompok -> status_dosbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);

        }
        // data
        $data = ['rs_kelompok' => $rs_kelompok];
        // view
        return view('tim_capstone.kelompok.kelompok-valid.index', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailKelompok($id)
    {

        // get data with pagination
        $kelompok = KelompokValidModel::getDataById($id);
        $rs_topik = KelompokValidModel::getTopik();
        $rs_mahasiswa = KelompokValidModel::listKelompokMahasiswa($id);
        $rs_mahasiswa_nokel = KelompokValidModel::listKelompokMahasiswaNokel($kelompok->id_topik);
        $rs_dosbing = MahasiswaKelompokModel::getAkunDosbingKelompok($id);
        $rs_dospenguji = KelompokValidModel::listDospenguji($id);
        $rs_dosbing_avail = KelompokValidModel::listDosbingAvail();
        $rs_dosbing_avail_arr = [];

        // dd($rs_dosbing_avail);

        foreach ($rs_dosbing_avail as $key => $dosbing) {
            $check_dosen_kelompok = KelompokValidModel::checkDosbing($id, $dosbing->user_id);
            if ($check_dosen_kelompok) {
            }
            else {
                $rs_dosbing_avail_arr[] = $dosbing;
            }
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

        // check
        if (empty($kelompok)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/kelompok-valid');
        }

        // status color
        $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
        $kelompok -> status_dosbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
        $kelompok -> status_dosbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);

        // data
        $data = [
            'kelompok' => $kelompok,
            'rs_topik' => $rs_topik,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_dosbing' => $rs_dosbing,
            'rs_dospenguji' => $rs_dospenguji,
            'rs_mahasiswa_nokel' => $rs_mahasiswa_nokel,
            'rs_dosbing_avail' =>  $rs_dosbing_avail_arr
        ];
        // dd($data);

        // view
        return view('tim_capstone.kelompok.kelompok-valid.detail', $data);
    }

    public function deleteKelompokProcess($id)
    {

        // get data
        $kelompok = KelompokValidModel::getDataById($id);

        // if exist
        if (!empty($kelompok)) {
            $cekMhs=KelompokValidModel::getKelompokMhsAll($kelompok->id);
            foreach ($cekMhs as $key => $mhs) {
                KelompokValidModel::deleteKelompokMhs($mhs->id_mahasiswa);
            }

            if (KelompokValidModel::deleteJadwalSidangProposal($kelompok->id)) {
                if (KelompokValidModel::deleteKelompok($kelompok->id)) {
                    // flash message
                    session()->flash('success', 'Data berhasil dihapus.');
                    return back();
                } else {
                    // flash message
                    session()->flash('danger', 'Data gagal dihapus.');
                    return back();
                }
            } else {
                if (KelompokValidModel::deleteKelompok($kelompok->id)) {
                    // flash message
                    session()->flash('success', 'Data berhasil dihapus.');
                    return back();
                } else {
                    // flash message
                    session()->flash('danger', 'Data gagal dihapus.');
                    return back();
                }
            }
            // process

        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return back();
        }
    }

    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_kelompok = KelompokValidModel::getDataSearch($nama);
            // data
            $data = ['rs_kelompok' => $rs_kelompok, 'nama' => $nama];
            // view
            return view('tim_capstone.kelompok.kelompok-valid.index', $data);
        } else {
            return redirect('/admin/kelompok-valid');
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
            'hijau' => [
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
                    case 'hijau':
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
