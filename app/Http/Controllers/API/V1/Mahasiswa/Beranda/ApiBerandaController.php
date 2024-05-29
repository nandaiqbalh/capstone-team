<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Beranda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Beranda\ApiBerandaModel;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class ApiBerandaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($this->validateUser($user)) {
                $kelompok = ApiBerandaModel::pengecekan_kelompok_mahasiswa($user->user_id);

                if ($this->validateKelompok($kelompok)) {

                    // sidang proposal
                    $rsSidang = ApiBerandaModel::sidangProposalByKelompok($kelompok->id);
                    if ($kelompok -> status_sidang_proposal == "Lulus Sidang Proposal") {
                        $sidang_proposal = "Lulus Sidang Proposal";
                    } else if($kelompok -> status_sidang_proposal == null){
                        $sidang_proposal = "Belum ada jadwal sidang";
                    } else if ($rsSidang != null) {
                        $waktuSidang = strtotime($rsSidang->waktu);

                        // Mendapatkan nama hari dalam bahasa Indonesia
                        $rsSidang->hari_sidang = strftime('%A', $waktuSidang);
                        $rsSidang->hari_sidang = $this->convertDayToIndonesian($rsSidang->hari_sidang);

                        // Mendapatkan tanggal sidang dalam format d-m-Y
                        $rsSidang->tanggal_sidang = date('d-m-Y', $waktuSidang);

                        // Mendapatkan nama bulan dalam bahasa Indonesia
                        $bulanSidangIndo = $this->convertMonthToIndonesian(date('F', $waktuSidang));

                        // Menggabungkan nama hari, tanggal, dan bulan dalam bahasa Indonesia
                        $sidang_proposal = $rsSidang->hari_sidang . ', ' . date('d', $waktuSidang) . ' ' . $bulanSidangIndo . ' ' . date('Y', $waktuSidang);

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
                    $pendaftaran_ta = ApiBerandaModel::cekStatusPendaftaranSidangTA($user->user_id);
                    $kelompok_mhs = ApiBerandaModel::checkKelompokMhs($user->user_id);
                    $sidang_ta = ApiBerandaModel::sidangTugasAkhirByMahasiswa($user->user_id);

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
                            if ($kelompok -> status_expo == "Lulus Expo Project") {
                                $sidang_ta = "Belum menyelesaikan capstone";
                            } else {
                                $sidang_ta = "Belum Mendaftar Sidang TA";
                            }
                        }
                    }

                    $data = [
                        'sidang_proposal' => $sidang_proposal,
                        'expo' => $expo,
                        'sidang_ta' => $sidang_ta
                    ];

                    $response = $this->successResponse('Berhasil!', $data);

                } else {

                    if ($kelompok != null && $kelompok -> nomor_kelompok == null) {
                        $data = [
                            'sidang_proposal' => "Kelompok Anda belum valid",
                            'expo' => "Kelompok Anda belum valid",
                            'sidang_ta' => "Anda belum menyelesaikan capstone"
                        ];

                        $response = $this->successResponse('Berhasil!', $data);
                    } else {
                        $data = [
                            'sidang_proposal' => "Anda belum mendaftar capstone",
                            'expo' => "Anda belum mendaftar capstone",
                            'sidang_ta' => "Anda belum menyelesaikan capstone"
                        ];
                        $response = $this->successResponse('Berhasil!', $data);
                    }
                }
            } else {
                $response = $this->failureResponse('Pengguna tidak ditemukan!');
            }
        } catch (JWTException $e) {
            $response = $this->failureResponse('Token is Invalid');
        }

        return response()->json($response);
    }

    // Fungsi untuk validasi pengguna
    private function validateUser($user)
    {
        return $user && $user->user_active == 1;
    }

    // Fungsi untuk validasi kelompok
    private function validateKelompok($kelompok)
    {
        return $kelompok && $kelompok->nomor_kelompok;
    }

    // Fungsi untuk validasi data expo
    private function validateExpoData($rs_expo)
    {
        return $rs_expo !== null;
    }

     // Fungsi untuk menangani respons sukses
     private function successResponse($statusMessage, $data)
     {
         return [
             'success' => true,
             'status' => $statusMessage,
             'data' => $data,
         ];
     }

     // Fungsi untuk menangani respons kegagalan
     private function failureResponse($statusMessage, $data = null)
     {
         return [
             'success' => false,
             'status' => $statusMessage,
             'data' => $data,
         ];
     }

}
