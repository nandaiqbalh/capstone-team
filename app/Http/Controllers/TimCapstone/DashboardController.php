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
        $rs_siklus = Dashmo::getSiklusAktif();

        // data

        $data = [
            'rs_siklus' => $rs_siklus,
            'rs_broadcast' => $rs_broadcast,
        ];

        //view
        return view('tim_capstone.dashboard.index', $data);
    }

    public function indexTimCapstone()
    {
        // get data with pagination
        $rs_broadcast = Dashmo::getDataWithPagination();
        $rs_siklus = Dashmo::getSiklusAktif();

        $rs_mahasiswa = Dashmo::getDataBalancingDosbingMahasiswa();
        $rs_pengujian_proposal = Dashmo::getJadwalSidangProposalTerdekat();
        $rs_pengujian_ta = Dashmo::getJadwalSidangTATerdekat();
        $kelompok = Dashmo::getJumlahKelompokMendaftar();
        $kelompok_c100 = Dashmo::getJumlahC100();
        $kelompok_sidang_proposal = Dashmo::getJumlahSidangProposal();
        $kelompok_c200 = Dashmo::getJumlahC200();
        $kelompok_c300 = Dashmo::getJumlahC300();
        $kelompok_c400 = Dashmo::getJumlahC400();
        $kelompok_c500 = Dashmo::getJumlahC500();
        $kelompok_mendaftar_expo = Dashmo::getJumlahKelompokMendaftarExpo();
        $kelompok_lulus_expo = Dashmo::getJumlahLulusExpo();

        // data
        $data = [
            'rs_siklus' => $rs_siklus,
            'rs_broadcast' => $rs_broadcast,
            'kelompok' => $kelompok,
            'kelompok_c100' => $kelompok_c100,
            'kelompok_sidang_proposal' => $kelompok_sidang_proposal,
            'kelompok_c200' => $kelompok_c200,
            'kelompok_c300' => $kelompok_c300,
            'kelompok_c400' => $kelompok_c400,
            'kelompok_c500' => $kelompok_c500,
            'kelompok_mendaftar_expo' => $kelompok_mendaftar_expo,
            'kelompok_lulus_expo' => $kelompok_lulus_expo,

        ];

        //view
        return view('tim_capstone.dashboard-tim-capstone.index', $data);
    }

    public function filterSiklusByTimCapstone(Request $request)
    {
        // data request
        $id_siklus = $request->id_siklus;

        // new search or reset
        if ($request->action == 'filter') {
             // get data with pagination
             $rs_broadcast = Dashmo::getDataWithPagination($id_siklus);
             $rs_siklus = Dashmo::getSiklusAktif($id_siklus);

             $kelompok = Dashmo::filterSiklusJumlahKelompokMendaftar($id_siklus);
             $kelompok_c100 = Dashmo::filterSiklusJumlahC100($id_siklus);
             $kelompok_sidang_proposal = Dashmo::filterSiklusJumlahSidangProposal($id_siklus);
             $kelompok_c200 = Dashmo::filterSiklusJumlahC200($id_siklus);
             $kelompok_c300 = Dashmo::filterSiklusJumlahC300($id_siklus);
             $kelompok_c400 = Dashmo::filterSiklusJumlahC400($id_siklus);
             $kelompok_c500 = Dashmo::filterSiklusJumlahC500($id_siklus);
             $kelompok_mendaftar_expo = Dashmo::filterSiklusJumlahKelompokMendaftarExpo($id_siklus);
             $kelompok_lulus_expo = Dashmo::filterSiklusJumlahLulusExpo($id_siklus);

             $siklus = Dashmo::getSiklusById($id_siklus);

             // data
             $data = [
                 'rs_siklus' => $rs_siklus,
                 'rs_broadcast' => $rs_broadcast,
                 'kelompok' => $kelompok,
                 'kelompok_c100' => $kelompok_c100,
                 'kelompok_sidang_proposal' => $kelompok_sidang_proposal,
                 'kelompok_c200' => $kelompok_c200,
                 'kelompok_c300' => $kelompok_c300,
                 'kelompok_c400' => $kelompok_c400,
                 'kelompok_c500' => $kelompok_c500,
                 'kelompok_mendaftar_expo' => $kelompok_mendaftar_expo,
                 'kelompok_lulus_expo' => $kelompok_lulus_expo,
                 'siklus' => $siklus,

             ];
            return view('tim_capstone.dashboard-tim-capstone.index', $data);
        } else {
            return view('tim_capstone.dashboard-tim-capstone.index', $data);
        }
    }

    public function indexMahasiswa()
    {
        // get data with pagination
        $rs_broadcast = Dashmo::getDataWithPagination();
        $rs_siklus = Dashmo::getSiklusAktif();

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

            } else if ($kelompok -> status_sidang_proposal == "Lulus Sidang Proposal") {
                $sidang_proposal = "Lulus Sidang Proposal";
            } else if($kelompok -> status_sidang_proposal == null){
                $sidang_proposal = "Belum ada jadwal sidang";
            } else {
                $sidang_proposal = $kelompok -> status_sidang_proposal;
            }

            // expo
            if ($kelompok -> status_expo == "Lulus Expo Project") {
                $expo = "Lulus Expo Project";
            } else if($kelompok -> status_expo == null){
                $expo = "Belum mendaftar Expo";
            } else {
                $expo = $kelompok -> status_expo;
            }

            // sidang ta
            $pendaftaran_ta = Dashmo::cekStatusPendaftaranSidangTA($user->user_id);
            $kelompok_mhs = Dashmo::checkKelompokMhs($user->user_id);
             $sidang_ta = Dashmo::sidangTugasAkhirByMahasiswa($user->user_id);
            if ($kelompok_mhs -> status_tugas_akhir == "Lulus Sidang TA") {
                $sidang_ta = "Lulus Sidang TA";
            } else if ($sidang_ta != null) {
                $waktuSidang = strtotime($sidang_ta->waktu);

                // Mendapatkan nama hari dalam bahasa Indonesia
                $sidang_ta->hari_sidang = strftime('%A', $waktuSidang);
                $sidang_ta->hari_sidang = $this->convertDayToIndonesian($sidang_ta->hari_sidang);

                // Mendapatkan tanggal sidang dalam format d-m-Y
                $sidang_ta->tanggal_sidang = date('d-m-Y', $waktuSidang);

                // Mendapatkan nama bulan dalam bahasa Indonesia
                $bulanSidangIndo = $this->convertMonthToIndonesian(date('F', $waktuSidang));

                // Menggabungkan nama hari, tanggal, dan bulan dalam bahasa Indonesia
                $sidang_ta = $sidang_ta->hari_sidang . ', ' . date('d', $waktuSidang) . ' ' . $bulanSidangIndo . ' ' . date('Y', $waktuSidang);

             } else {
                if ($kelompok_mhs->status_tugas_akhir != null) {
                    $sidang_ta = $kelompok_mhs->status_tugas_akhir;
                } else {
                    $sidang_ta = "Belum menyelesaikan capstone";
                }
            }


            // data
            $data = [
                'rs_broadcast' => $rs_broadcast,
                'sidang_proposal' => $sidang_proposal,
                'expo' => $expo,
                'sidang_ta' => $sidang_ta,
                'rs_siklus' => $rs_siklus,

            ];


        } else {

            if ($kelompok != null && $kelompok -> nomor_kelompok == null) {
                $data = [
                    'rs_broadcast' => $rs_broadcast,
                    'sidang_proposal' => "Kelompok Anda belum valid",
                    'expo' => "Kelompok Anda belum valid",
                    'sidang_ta' => "Anda belum menyelesaikan capstone",
                    'rs_siklus' => $rs_siklus,

                ];

            } else {
                $data = [
                    'rs_broadcast' => $rs_broadcast,
                    'sidang_proposal' => "Anda belum mendaftar capstone",
                    'expo' => "Anda belum mendaftar capstone",
                    'sidang_ta' => "Anda belum menyelesaikan capstone",
                    'rs_siklus' => $rs_siklus,

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

    public function indexDosen()
    {
        // get data with pagination
        $rs_broadcast = Dashmo::getDataWithPagination();
        $rs_siklus = Dashmo::getSiklusAktif();

        $rs_kelompok = Dashmo::getDataBalancingDosbingKelompok();
        $rs_mahasiswa = Dashmo::getDataBalancingDosbingMahasiswa();
        $rs_pengujian_proposal = Dashmo::getJadwalSidangProposalTerdekat();
        $rs_pengujian_ta = Dashmo::getJadwalSidangTATerdekat();

        if ($rs_pengujian_proposal != null) {
            $waktuSidang = strtotime($rs_pengujian_proposal->waktu);

            $rs_pengujian_proposal->hari_sidang = strftime('%A', $waktuSidang);
            $rs_pengujian_proposal->hari_sidang = $this->convertDayToIndonesian($rs_pengujian_proposal->hari_sidang);
            $rs_pengujian_proposal->tanggal_sidang = date('d-m-Y', $waktuSidang);
            $rs_pengujian_proposal->waktu_sidang = date('H:i:s', $waktuSidang);

            $waktuSelesai = strtotime($rs_pengujian_proposal->waktu_selesai);
            $rs_pengujian_proposal->waktu_selesai = date('H:i:s', $waktuSelesai);
        }

        if ($rs_pengujian_ta != null) {
            $waktuSidang = strtotime($rs_pengujian_ta->waktu);

            $rs_pengujian_ta->hari_sidang = strftime('%A', $waktuSidang);
            $rs_pengujian_ta->hari_sidang = $this->convertDayToIndonesian($rs_pengujian_ta->hari_sidang);
            $rs_pengujian_ta->tanggal_sidang = date('d-m-Y', $waktuSidang);
            $rs_pengujian_ta->waktu_sidang = date('H:i:s', $waktuSidang);

            $waktuSelesai = strtotime($rs_pengujian_ta->waktu_selesai);
            $rs_pengujian_ta->waktu_selesai = date('H:i:s', $waktuSelesai);
        }

        $rs_jumlah_sidang_proposal = Dashmo::getDataBalancingPengujiProposal();
        $rs_jumlah_sidang_ta = Dashmo::getDataBalancingPengujiTA();


        // data
        $data = [
            'rs_broadcast' => $rs_broadcast,
            'rs_kelompok' => $rs_kelompok,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_pengujian_proposal' => $rs_pengujian_proposal,
            'rs_jumlah_sidang_proposal' => $rs_jumlah_sidang_proposal,
            'rs_pengujian_ta' => $rs_pengujian_ta,
            'rs_jumlah_sidang_ta' => $rs_jumlah_sidang_ta,
            'rs_siklus' => $rs_siklus,

        ];

        //view
        return view('dosen.dashboard-dosen.index', $data);
    }

    public function filterSiklusByDosen(Request $request)
    {
        // data request
        $id_siklus = $request->id_siklus;

        // new search or reset
        if ($request->action == 'filter') {
             // get data with pagination
            $rs_broadcast = Dashmo::getDataWithPagination();
            $rs_siklus = Dashmo::getSiklusAktif();
            $siklus = Dashmo::getSiklusById($id_siklus);

            $rs_kelompok = Dashmo::filterSiklusDataBalancingDosbingKelompok($id_siklus);
            $rs_mahasiswa = Dashmo::filterSiklusBalancingDosbingMahasiswa($id_siklus);
            $rs_pengujian_proposal = Dashmo::getJadwalSidangProposalTerdekat();
            $rs_pengujian_ta = Dashmo::getJadwalSidangTATerdekat();

            if ($rs_pengujian_proposal != null) {
                $waktuSidang = strtotime($rs_pengujian_proposal->waktu);

                $rs_pengujian_proposal->hari_sidang = strftime('%A', $waktuSidang);
                $rs_pengujian_proposal->hari_sidang = $this->convertDayToIndonesian($rs_pengujian_proposal->hari_sidang);
                $rs_pengujian_proposal->tanggal_sidang = date('d-m-Y', $waktuSidang);
                $rs_pengujian_proposal->waktu_sidang = date('H:i:s', $waktuSidang);

                $waktuSelesai = strtotime($rs_pengujian_proposal->waktu_selesai);
                $rs_pengujian_proposal->waktu_selesai = date('H:i:s', $waktuSelesai);
            }

            if ($rs_pengujian_ta != null) {
                $waktuSidang = strtotime($rs_pengujian_ta->waktu);

                $rs_pengujian_ta->hari_sidang = strftime('%A', $waktuSidang);
                $rs_pengujian_ta->hari_sidang = $this->convertDayToIndonesian($rs_pengujian_ta->hari_sidang);
                $rs_pengujian_ta->tanggal_sidang = date('d-m-Y', $waktuSidang);
                $rs_pengujian_ta->waktu_sidang = date('H:i:s', $waktuSidang);

                $waktuSelesai = strtotime($rs_pengujian_ta->waktu_selesai);
                $rs_pengujian_ta->waktu_selesai = date('H:i:s', $waktuSelesai);
            }

            $rs_jumlah_sidang_proposal = Dashmo::filterSiklusBalancingPengujiProposal($id_siklus);
            $rs_jumlah_sidang_ta = Dashmo::filterSiklusBalancingPengujiTA($id_siklus);

            // data
            $data = [
                'rs_broadcast' => $rs_broadcast,
                'rs_kelompok' => $rs_kelompok,
                'rs_mahasiswa' => $rs_mahasiswa,
                'rs_pengujian_proposal' => $rs_pengujian_proposal,
                'rs_jumlah_sidang_proposal' => $rs_jumlah_sidang_proposal,
                'rs_pengujian_ta' => $rs_pengujian_ta,
                'rs_jumlah_sidang_ta' => $rs_jumlah_sidang_ta,
                'rs_siklus' => $rs_siklus,
                'siklus' => $siklus,

            ];
            return view('dosen.dashboard-dosen.index', $data);
        } else {
            return view('dosen.dashboard-dosen.index', $data);
        }
    }
}
