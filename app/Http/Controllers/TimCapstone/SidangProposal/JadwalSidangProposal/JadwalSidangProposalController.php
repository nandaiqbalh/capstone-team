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
        $rs_siklus = JadwalSidangProposalModel::getSiklus();
        $rs_kelompok = JadwalSidangProposalModel::getSiklus();


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
            'rs_siklus' => $rs_siklus,
            'rs_kelompok' => $rs_kelompok
        ];

        // dd($data);
        // view
        return view('tim_capstone.sidang-proposal.jadwal-sidang-proposal.index', $data);
    }

    public function deleteJadwalSidangProposalProcess($id)
    {
        // get data
        $delete = JadwalSidangProposalModel::getDataById($id);

        // if exist
        if (!empty($delete)) {
            // process
            if (JadwalSidangProposalModel::deleteJadwalSidangProposal($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
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
