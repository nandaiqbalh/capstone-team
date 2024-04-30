<?php

namespace App\Http\Controllers\TimCapstone\SidangTA\JadwalSidangTA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\SidangTA\JadwalSidangTA\JadwalSidangTAModel;
use Illuminate\Support\Facades\Hash;

class JadwalSidangTAController extends BaseController
{

    public function index()
    {

        // get data with pagination
        $rs_sidang = JadwalSidangTAModel::getDataWithPagination();
        $rs_periode = JadwalSidangTAModel::getPeriode();

        foreach ($rs_sidang as $sidang_ta) {
            if ($sidang_ta != null) {
                $waktuSidang = strtotime($sidang_ta->waktu);

                $sidang_ta->hari_sidang = strftime('%A', $waktuSidang);
                $sidang_ta->hari_sidang = $this->convertDayToIndonesian($sidang_ta->hari_sidang);
                $sidang_ta->tanggal_sidang = date('d-m-Y', $waktuSidang);
                $sidang_ta->waktu_sidang = date('H:i:s', $waktuSidang);

                $waktuSelesai = strtotime($sidang_ta->waktu_selesai);
                $sidang_ta->waktu_selesai = date('H:i:s', $waktuSelesai);
            }
            $sidang_ta -> status_sidang_color = $this->getStatusColor($sidang_ta->status_tugas_akhir);
            $sidang_ta -> status_lta_color = $this->getStatusColor($sidang_ta->file_status_lta);

        }


        // data
        $data = [
            'rs_sidang' => $rs_sidang,
            'rs_periode' => $rs_periode,

        ];

        // dd($data);
        // view
        return view('tim_capstone.sidang-ta.jadwal-sidang-ta.index', $data);
    }

    public function detailJadwalSidangTA($id)
    {
       // get data with pagination
       $mahasiswa = JadwalSidangTAModel::getDataDetailMahasiswaSidang($id);
       $rs_dosbing = JadwalSidangTAModel::getAkunDosbingKelompok($mahasiswa->id_kelompok);
       $rs_penguji_ta = JadwalSidangTAModel::getAkunPengujiTAKelompok($id);
       $rs_mahasiswa = JadwalSidangTAModel::listMahasiswaSendiri($id, $mahasiswa->id_kelompok);

       // get jadwal sidang
       $jadwal_sidang = JadwalSidangTAModel::getJadwalSidangTA($id);
       if($jadwal_sidang != null){
           $waktuSidang = strtotime($jadwal_sidang->waktu);

           $jadwal_sidang->hari_sidang = strftime('%A', $waktuSidang);
           $jadwal_sidang->hari_sidang = $this->convertDayToIndonesian($jadwal_sidang->hari_sidang);
           $jadwal_sidang->tanggal_sidang = date('d-m-Y', $waktuSidang);
           $jadwal_sidang->waktu_sidang = date('H:i:s', $waktuSidang);

           $waktuSelesai = strtotime($jadwal_sidang->waktu_selesai);
           $jadwal_sidang->waktu_selesai = date('H:i:s', $waktuSelesai);

       }

       $rs_ruang_sidang = JadwalSidangTAModel::getRuangSidang();

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
           return redirect('/tim-capstone/pengujian-ta');
       }

       $mahasiswa -> status_kelompok_color = $this->getStatusColor($mahasiswa->status_kelompok);
       $mahasiswa -> status_dokumen_color = $this->getStatusColor($mahasiswa->file_status_c100);
       $mahasiswa -> status_sidang_color = $this->getStatusColor($mahasiswa->status_tugas_akhir);
       $mahasiswa -> status_lta_color = $this->getStatusColor($mahasiswa->file_status_lta);

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
        return view('tim_capstone.sidang-ta.jadwal-sidang-ta.detail', $data);
    }


    public function toLulusSidangTA($id)
    {
        // get data
        $dataMahasiswa = JadwalSidangTAModel::getKelompokMhs($id);

        // if exist
        if ($dataMahasiswa != null) {

            $paramKelompokMhs = [
                'status_tugas_akhir' => 'Lulus Sidang TA',
                'status_individu' => 'Lulus Sidang TA',
                'is_selesai' => 1,
            ];

            JadwalSidangTAModel::updateKelompokMhs($dataMahasiswa -> id_mahasiswa, $paramKelompokMhs);

            session()->flash('success', 'Data berhasil diperbaharui');
            return redirect('/tim-capstone/jadwal-sidang-ta');

        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/tim-capstone/jadwal-sidang-ta');
        }
    }

    public function toGagalSidangTA($id)
    {
        // get data
        $dataMahasiswa = JadwalSidangTAModel::getKelompokMhs($id);

        // if exist
        if ($dataMahasiswa != null) {

            $paramKelompokMhs = [
                'status_tugas_akhir' => 'Gagal Sidang TA',
                'status_individu' => 'Gagal Sidang TA',
                'is_mendaftar_sidang' => '0',
            ];


            $update = JadwalSidangTAModel::updateKelompokMhs($dataMahasiswa -> id_mahasiswa, $paramKelompokMhs);

            if ($update) {
                session()->flash('success', 'Data berhasil diperbaharui');
                return redirect('/tim-capstone/jadwal-sidang-ta');
            } else {
                session()->flash('danger', 'Data tidak ditemukan.');
                return redirect('/tim-capstone/jadwal-sidang-ta');
            }

        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/tim-capstone/jadwal-sidang-ta');
        }
    }

    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;
        $rs_periode = JadwalSidangTAModel::getPeriode();

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_sidang = JadwalSidangTAModel::getDataSearch($nama);

            foreach ($rs_sidang as $sidang_ta) {
                if ($sidang_ta != null) {
                    $waktuSidang = strtotime($sidang_ta->waktu);

                    $sidang_ta->hari_sidang = strftime('%A', $waktuSidang);
                    $sidang_ta->hari_sidang = $this->convertDayToIndonesian($sidang_ta->hari_sidang);
                    $sidang_ta->tanggal_sidang = date('d-m-Y', $waktuSidang);
                    $sidang_ta->waktu_sidang = date('H:i:s', $waktuSidang);

                    $waktuSelesai = strtotime($sidang_ta->waktu_selesai);
                    $sidang_ta->waktu_selesai = date('H:i:s', $waktuSelesai);
                }
                $sidang_ta -> status_sidang_color = $this->getStatusColor($sidang_ta->status_tugas_akhir);
                $sidang_ta -> status_lta_color = $this->getStatusColor($sidang_ta->file_status_lta);

            }

            // data
            $data = ['rs_sidang' => $rs_sidang, 'rs_periode' => $rs_periode,  'nama' => $nama];
            // view
            return view('tim_capstone.sidang-ta.jadwal-sidang-ta.index', $data);
        } else {
            return redirect('/tim-capstone/jadwal-sidang-ta');
        }
    }

    public function filterPeriodeKelompok(Request $request)
    {
        // data request
        $id_periode = $request->id_periode;

        // new search or reset
        if ($request->action == 'filter') {
            $rs_sidang = JadwalSidangTAModel::filterPeriodeKelompok($id_periode);
            $rs_periode = JadwalSidangTAModel::getPeriode();
            $periode = JadwalSidangTAModel::getPeriodeById($id_periode);

            foreach ($rs_sidang as $sidang_ta) {
                if ($sidang_ta != null) {
                    $waktuSidang = strtotime($sidang_ta->waktu);

                    $sidang_ta->hari_sidang = strftime('%A', $waktuSidang);
                    $sidang_ta->hari_sidang = $this->convertDayToIndonesian($sidang_ta->hari_sidang);
                    $sidang_ta->tanggal_sidang = date('d-m-Y', $waktuSidang);
                    $sidang_ta->waktu_sidang = date('H:i:s', $waktuSidang);

                    $waktuSelesai = strtotime($sidang_ta->waktu_selesai);
                    $sidang_ta->waktu_selesai = date('H:i:s', $waktuSelesai);
                }
                $sidang_ta -> status_sidang_color = $this->getStatusColor($sidang_ta->status_tugas_akhir);
                $sidang_ta -> status_lta_color = $this->getStatusColor($sidang_ta->file_status_lta);

            }

            // data
            $data = [
                'rs_sidang' => $rs_sidang,
                'rs_periode' => $rs_periode,
                'periode' => $periode,
            ];
            // view
            return view('tim_capstone.sidang-ta.jadwal-sidang-ta.index', $data);
        } else {
            return redirect('/tim-capstone/jadwal-sidang-ta');
        }
    }

}
