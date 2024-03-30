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

    public function toLulusSidangProposal($id)
    {
        // get data
        $dataKelompok = JadwalSidangProposalModel::getKelompokById($id);

        // if exist
        if ($dataKelompok != null) {

            $paramKelompok = [
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
                'status_kelompok' => 'C100 Telah Disetujui!',
                'status_dosen_pembimbing_2' => 'Menyetujui Dokumen C100!',
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
