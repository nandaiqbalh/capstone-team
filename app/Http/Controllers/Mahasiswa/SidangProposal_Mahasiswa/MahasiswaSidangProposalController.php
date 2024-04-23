<?php

namespace App\Http\Controllers\Mahasiswa\SidangProposal_Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Mahasiswa\SidangProposal_Mahasiswa\MahasiswaSidangProposalModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDO;

class MahasiswaSidangProposalController extends BaseController
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
        $kelompok = MahasiswaSidangProposalModel::pengecekan_kelompok_mahasiswa(Auth::user()->user_id);

        if ($kelompok != null) {
            $rs_sidang = MahasiswaSidangProposalModel::sidangProposalByKelompok($kelompok->id);

            $akun_mahasiswa = MahasiswaSidangProposalModel::getAkunByID(Auth::user()->user_id);
            $siklusSudahPunyaKelompok = MahasiswaSidangProposalModel::checkApakahSiklusMasihAktif($akun_mahasiswa ->id_siklus);

            // dari tabel kelompok_mhs
            $akun_mahasiswa = MahasiswaSidangProposalModel::getAkunByID(Auth::user()->user_id);

            $rs_mahasiswa = MahasiswaSidangProposalModel::listKelompokMahasiswa($kelompok->id_kelompok);
            $rs_dosbing = MahasiswaSidangProposalModel::getAkunDosbingKelompok($kelompok->id_kelompok);
            $rs_dospeng = MahasiswaSidangProposalModel::getAkunDospengKelompok($kelompok->id_kelompok);

            $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
            $kelompok -> status_dokumen_color = $this->getStatusColor($kelompok->file_status_c100);
            $kelompok -> status_sidang_color = $this->getStatusColor($kelompok->status_sidang_proposal);

            $kelompok -> status_penguji1_color = $this->getStatusColor($kelompok->status_dosen_penguji_1);
            $kelompok -> status_penguji2_color = $this->getStatusColor($kelompok->status_dosen_penguji_2);
            $kelompok -> status_pembimbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
            $kelompok -> status_pembimbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);

            foreach ($rs_dosbing as $dosbing) {

                if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_1) {
                    $dosbing->jenis_dosen = 'Pembimbing 1';
                    $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_1;
                } else if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_2) {
                    $dosbing->jenis_dosen = 'Pembimbing 2';
                    $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_2;
                }
            }

            foreach ($rs_dospeng as $dospeng) {

                if ($dospeng->user_id == $kelompok->id_dosen_penguji_1) {
                    $dospeng->jenis_dosen = 'Penguji 1';
                    $dospeng->status_dosen = $kelompok->status_dosen_penguji_1;
                } else if ($dospeng->user_id == $kelompok->id_dosen_penguji_2) {
                    $dospeng->jenis_dosen = 'Penguji 2';
                    $dospeng->status_dosen = $kelompok->status_dosen_penguji_2;
                }
            }

            if($rs_sidang != null){
                // proses waktu sidang
                $waktuSidang = strtotime($rs_sidang->waktu);

                $rs_sidang->hari_sidang = strftime('%A', $waktuSidang);
                $rs_sidang->hari_sidang = $this->convertDayToIndonesian($rs_sidang->hari_sidang);
                $rs_sidang->tanggal_sidang = date('d-m-Y', $waktuSidang);
                $rs_sidang->waktu_sidang = date('H:i:s', $waktuSidang);

                $waktuSelesai = strtotime($rs_sidang->waktu_selesai);
                $rs_sidang->waktu_selesai = date('H:i:s', $waktuSelesai);
            }


            $data = [
                'kelompok'  => $kelompok,
                'rs_mahasiswa' => $rs_mahasiswa,
                'rs_dosbing' => $rs_dosbing,
                'rs_dospeng' => $rs_dospeng,
                'rs_sidang' => $rs_sidang,
                'siklus_sudah_punya_kelompok' => $siklusSudahPunyaKelompok,
                'akun_mahasiswa' => $akun_mahasiswa,
            ];
            // dd($data);
        } else {
            $getAkun = MahasiswaSidangProposalModel::getAkunBelumPunyaKelompok(Auth::user()->user_id);
            // data
            $data = [
                'kelompok'  => $kelompok,
                'getAkun' => $getAkun,
            ];

        }
        // view

        return view('mahasiswa.sidang-proposal-mahasiswa.detail', $data);
    }
}
