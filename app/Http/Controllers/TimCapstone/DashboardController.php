<?php

namespace App\Http\Controllers\TimCapstone;

use App\Http\Controllers\TimCapstone\BaseController;
use Illuminate\Http\Request;
use App\Models\TimCapstone\DashboardModel as Dashmo;
use Illuminate\Support\Facades\Auth;

class DashboardController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get data with pagination
        $rs_broadcast = Dashmo::getDataWithPagination();


        // data

        $data = [
            'rs_broadcast' => $rs_broadcast,

        ];

        //view
        return view('tim_capstone.dashboard.index', $data);
    }

    public function indexMahasiswa()
    {
        // get data with pagination
          $rs_broadcast = Dashmo::getDataWithPagination();

        $user = Auth::user();
        $kelompok = Dashmo::pengecekan_kelompok_mahasiswa($user->user_id);

        if ($this->validateKelompok($kelompok)) {

            // sidang proposal
            $rsSidang = Dashmo::sidangProposalByKelompok($kelompok->id);
            if ($rsSidang != null) {
                $waktuSidang = strtotime($rsSidang->waktu);

                $rsSidang->hari_sidang = strftime('%A', $waktuSidang);
                $rsSidang->hari_sidang = $this->convertDayToIndonesian($rsSidang->hari_sidang);
                $rsSidang->tanggal_sidang = date('d-m-Y', $waktuSidang);

                $sidang_proposal = $rsSidang->hari_sidang . ', ' . date('d F Y', strtotime($rsSidang->tanggal_sidang));

            } else if ($kelompok -> status_sidang_proposal == "Lulus Sidang Proposal!") {
                $sidang_proposal = "Lulus Sidang Proposal!";
            } else if($kelompok -> status_sidang_proposal == null){
                $sidang_proposal = "Belum ada jadwal sidang!";
            } else {
                $sidang_proposal = $kelompok -> status_sidang_proposal;
            }

            // expo
            if ($kelompok -> status_expo == "Lulus Expo Project!") {
                $expo = "Lulus Expo Project!";
            } else if($kelompok -> status_expo == null){
                $expo = "Belum mendaftar Expo!";
            } else {
                $expo = $kelompok -> status_expo;
            }

            // sidang ta
            $pendaftaran_ta = Dashmo::cekStatusPendaftaranSidangTA($user->user_id);
            $kelompok_mhs = Dashmo::checkKelompokMhs($user->user_id);
             $sidang_ta = Dashmo::sidangTugasAkhirByMahasiswa($user->user_id);
             if ($sidang_ta != null) {
                 $waktuSidang = strtotime($sidang_ta->waktu);

                 $sidang_ta->hari_sidang = strftime('%A', $waktuSidang);
                 $sidang_ta->hari_sidang = $this->convertDayToIndonesian($sidang_ta->hari_sidang);
                 $sidang_ta->tanggal_sidang = date('d-m-Y', $waktuSidang);

                 $sidang_ta = $sidang_ta->hari_sidang . ', ' . date('d F Y', strtotime($rsSidang->tanggal_sidang));

             } else if ($kelompok_mhs -> status_individu == "Lulus Sidang TA!") {
                $sidang_ta = "Lulus Sidang TA!";
             } else {
                $sidang_ta = "Belum menyelesaikan capstone!";
             }

            // data
            $data = [
                'rs_broadcast' => $rs_broadcast,
                'sidang_proposal' => $sidang_proposal,
                'expo' => $expo,
                'sidang_ta' => $sidang_ta
            ];


        } else {

            if ($kelompok != null && $kelompok -> nomor_kelompok == null) {
                $data = [
                    'rs_broadcast' => $rs_broadcast,
                    'sidang_proposal' => "Kelompok Anda belum valid!",
                    'expo' => "Kelompok Anda belum valid!",
                    'sidang_ta' => "Anda belum menyelesaikan capstone!"
                ];

            } else {
                $data = [
                    'rs_broadcast' => $rs_broadcast,
                    'sidang_proposal' => "Anda belum mendaftar capstone!",
                    'expo' => "Anda belum mendaftar capstone!",
                    'sidang_ta' => "Anda belum menyelesaikan capstone!"
                ];
            }
        }

        //view
        return view('mahasiswa.dashboard-mahasiswa.index', $data);
    }

    private function validateKelompok($kelompok)
    {
        return $kelompok && $kelompok->nomor_kelompok;
    }

    private function convertDayToIndonesian($day)
    {
        // Mapping nama hari ke bahasa Indonesia
        $dayMappings = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        // Cek apakah nama hari ada di dalam mapping
        return array_key_exists($day, $dayMappings) ? $dayMappings[$day] : $day;
    }

    public function indexDosen()
    {
        // get data with pagination
        $rs_broadcast = Dashmo::getDataWithPagination();

        $rs_kelompok = Dashmo::getDataBalancingDosbingKelompok();
        $rs_mahasiswa = Dashmo::getDataBalancingDosbingMahasiswa();
        $rs_pengujian_proposal = Dashmo::getJadwalSidangProposalTerdekat();

        if ($rs_pengujian_proposal != null) {
            $waktuSidang = strtotime($rs_pengujian_proposal->waktu);

            $rs_pengujian_proposal->hari_sidang = strftime('%A', $waktuSidang);
            $rs_pengujian_proposal->hari_sidang = $this->convertDayToIndonesian($rs_pengujian_proposal->hari_sidang);
            $rs_pengujian_proposal->tanggal_sidang = date('d-m-Y', $waktuSidang);
            $rs_pengujian_proposal->waktu_sidang = date('H:i:s', $waktuSidang);

            $waktuSelesai = strtotime($rs_pengujian_proposal->waktu_selesai);
            $rs_pengujian_proposal->waktu_selesai = date('H:i:s', $waktuSelesai);
        }

        $rs_jumlah_sidang_proposal = Dashmo::getDataBalancingPengujiProposal();


        // data
        $data = [
            'rs_broadcast' => $rs_broadcast,
            'rs_kelompok' => $rs_kelompok,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_pengujian_proposal' => $rs_pengujian_proposal,
            'rs_jumlah_sidang_proposal' => $rs_jumlah_sidang_proposal,

        ];

        //view
        return view('dosen.dashboard-dosen.index', $data);
    }
}
