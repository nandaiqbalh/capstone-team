<?php

namespace App\Http\Controllers\TimCapstone\SidangProposal\JadwalSidangProposal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\SidangProposal\JadwalSidangProposal\JadwalSidangProposalModel;
use Illuminate\Support\Facades\Hash;


class JadwalSidangProposalController extends BaseController
{

    public function index()
    {

        // get data with pagination
        $rs_sidang = JadwalSidangProposalModel::getDataWithPagination();

        $rs_sidang = JadwalSidangProposalModel::getDataWithPagination();
        foreach ($rs_sidang as $ruang_sidang) {
            if ($ruang_sidang != null) {
                $waktuSidang = strtotime($ruang_sidang->waktu);

                $ruang_sidang->hari_sidang = strftime('%A', $waktuSidang);
                $ruang_sidang->hari_sidang = $this->convertDayToIndonesian($ruang_sidang->hari_sidang);
                $ruang_sidang->tanggal_sidang = date('d-m-Y', $waktuSidang);
                $ruang_sidang->waktu_sidang = date('H:i:s', $waktuSidang);

                $waktuSelesai = strtotime($ruang_sidang->waktu_selesai);
                $ruang_sidang->waktu_selesai = date('H:i:s', $waktuSelesai);
            }
        }
        // data
        $data = [
            'rs_sidang' => $rs_sidang,
        ];

        // dd($data);
        // view
        return view('tim_capstone.sidang-proposal.jadwal-sidang-proposal.index', $data);
    }

    public function detailKelompok($id)
    {

        // get data with pagination
        $kelompok = JadwalSidangProposalModel::getDataById($id);
        $rs_topik = JadwalSidangProposalModel::getTopik();
        $rs_mahasiswa = JadwalSidangProposalModel::listKelompokMahasiswa($id);
        $rs_dosbing = JadwalSidangProposalModel::getAkunDosbingKelompok($id);
        $rs_penguji_proposal = JadwalSidangProposalModel::getAkunPengujiProposalKelompok($id);

        // get jadwal sidang
        $jadwal_sidang = JadwalSidangProposalModel::getJadwalSidangProposal($id);
        if($jadwal_sidang != null){
            $waktuSidang = strtotime($jadwal_sidang->waktu);

            $jadwal_sidang->hari_sidang = strftime('%A', $waktuSidang);
            $jadwal_sidang->hari_sidang = $this->convertDayToIndonesian($jadwal_sidang->hari_sidang);
            $jadwal_sidang->tanggal_sidang = date('d-m-Y', $waktuSidang);
            $jadwal_sidang->waktu_sidang = date('H:i:s', $waktuSidang);

            $waktuSelesai = strtotime($jadwal_sidang->waktu_selesai);
            $jadwal_sidang->waktu_selesai = date('H:i:s', $waktuSelesai);

        }

        // penguji avaliable
        $rs_penguji = JadwalSidangProposalModel::getDosenPengujiProposal($id);

        $rs_ruang_sidang = JadwalSidangProposalModel::getRuangSidang();


        // dd($rs_penguji);


        foreach ($rs_dosbing as $dosbing) {

            if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_1) {
                $dosbing->jenis_dosen = 'Pembimbing 1';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_1;
            } else if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_2) {
                $dosbing->jenis_dosen = 'Pembimbing 2';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_2;
            }

        }

        foreach ($rs_penguji_proposal as $penguji_proposal) {

            if ($penguji_proposal->user_id == $kelompok->id_dosen_penguji_1) {
                $penguji_proposal->jenis_dosen = 'Penguji 1';
                $penguji_proposal->status_dosen = $kelompok->status_dosen_penguji_1;
            } else if ($penguji_proposal->user_id == $kelompok->id_dosen_penguji_2) {
                $penguji_proposal->jenis_dosen = 'Penguji 2';
                $penguji_proposal->status_dosen = $kelompok->status_dosen_penguji_2;
            }

        }

        // check
        if (empty($kelompok)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/kelompok');
        }

        $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
        $kelompok -> status_dokumen_color = $this->getStatusColor($kelompok->file_status_c100);
        $kelompok -> status_sidang_color = $this->getStatusColor($kelompok->status_sidang_proposal);

        // data
        $data = [
            'kelompok' => $kelompok,
            'rs_topik' => $rs_topik,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_dosbing' => $rs_dosbing,
            'rs_penguji_proposal' => $rs_penguji_proposal,
            'rs_penguji' => $rs_penguji,
            'rs_ruang_sidang' => $rs_ruang_sidang,
            'jadwal_sidang' => $jadwal_sidang,

        ];
        // dd($data);

        // view
        return view('tim_capstone.sidang-proposal.jadwal-sidang-proposal.detail', $data);
    }
    public function toLulusSidangProposal($id)
    {
        // get data
        $dataKelompok = JadwalSidangProposalModel::getKelompokById($id);

        // if exist
        if ($dataKelompok != null) {

            $paramKelompok = [
                'status_sidang_proposal' => 'Lulus Sidang Proposal!',
                'status_kelompok' => 'Lulus Sidang Proposal!',
                'is_sidang_proposal' => 1,
            ];

            JadwalSidangProposalModel::updateKelompok($dataKelompok -> id, $paramKelompok);

            session()->flash('success', 'Data berhasil diperbaharui!');
            return redirect('/admin/jadwal-sidang-proposal');

        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/jadwal-sidang-proposal');
        }
    }

    public function toGagalSidangProposal($id)
    {
        // get data
        $dataKelompok = JadwalSidangProposalModel::getKelompokById($id);

        // if exist
        if ($dataKelompok != null) {

            $paramKelompok = [
                'status_sidang_proposal' => 'Gagal Sidang Proposal!',
                'status_kelompok' => 'Gagal Sidang Proposal!',
            ];

            JadwalSidangProposalModel::updateKelompok($dataKelompok -> id, $paramKelompok);

            session()->flash('success', 'Data berhasil diperbaharui!');
            return redirect('/admin/jadwal-sidang-proposal');

        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/jadwal-sidang-proposal');
        }
    }

    public function deleteJadwalSidangProposalProcess($id)
    {
        // get data
        $delete = JadwalSidangProposalModel::getDataById($id);

        // if exist
        if (!empty($delete)) {

            $paramKelompok = [
                'status_sidang_proposal' => 'C100 Telah Disetujui!',
                'status_dosen_pembimbing_2' => 'Menunggu Persetujuan C100!',
                'id_dosen_penguji_1' => null,
                'status_dosen_penguji_1' => null,
                'id_dosen_penguji_2' => null,
                'status_dosen_penguji_2' => null,
            ];

            JadwalSidangProposalModel::updateKelompok($delete -> id_kelompok, $paramKelompok);
            // process
            if (JadwalSidangProposalModel::deleteJadwalSidangProposal($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/jadwal-sidang-proposal');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/jadwal-sidang-proposal');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/jadwal-sidang-proposal');
        }
    }

    private function convertDayToIndonesian($day)
    {
        $dayMappings = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        return array_key_exists($day, $dayMappings) ? $dayMappings[$day] : $day;
    }

}
