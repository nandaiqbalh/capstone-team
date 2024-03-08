<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\SidangProposal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Api\Mahasiswa\SidangProposal\ApiSidangProposalModel;
use App\Models\Api\Mahasiswa\Kelompok\ApiKelompokSayaModel;


class ApiSidangProposalController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Get the user  from the JWT token in the request headers
            $user = JWTAuth::parseToken()->authenticate();

            // Additional checks or actions based on the retrieved user
            if ($user != null && $user -> user_active == 1) {
                 // authorize

                // get data with pagination
                $rs_sidang = ApiSidangProposalModel::getData();
                $rs_siklus = ApiSidangProposalModel::getSiklus();
                $rs_kelompok = ApiSidangProposalModel::getSiklus();
                // data
                $response = [
                    'rs_sidang' => $rs_sidang,
                    'rs_siklus' => $rs_siklus,
                    'rs_kelompok' => $rs_kelompok
                ];
        // dd($data);
            } else {
                $response = [
                    'message' => 'Gagal',
                    'success' => false,
                    'status' => 'Gagal mendapatkan data dosen!',
                    'data' => null,
                ];
            }
        } catch (JWTException $e) {
            $response = [
                'message' => 'Gagal',
                'success' => false,
                'status' => 'Token is Invalid',
                'data' => null,
            ];

        }
        return response()->json($response);
    }

    public function sidangProposalByKelompok(Request $request)
    {
        try {
            // Get the user from the JWT token in the request headers
            $user = JWTAuth::parseToken()->authenticate();

            // Additional checks or actions based on the retrieved user
            if ($user !== null && $user->user_active == 1) {
                // get data kelompok
                $kelompok = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user->user_id);

                if ($kelompok != null) {
                    $rsSidang = ApiSidangProposalModel::sidangProposalByKelompok($kelompok->id);

                    $isSiklusAktif = ApiKelompokSayaModel::checkApakahSiklusMasihAktif($kelompok -> id_siklus);
                    if($isSiklusAktif == null){
                        // siklus sudah tidak aktif
                        $kelompok ->id_siklus = 0;

                        $response = [
                            'message' => 'Gagal',
                            'success' => false,
                            'status' => 'Siklus capstone sudah tidak aktif!',
                            'data' => null,
                        ];
                    } else {

                        if($kelompok -> nomor_kelompok == null){
                            $response = [
                                'message' => 'Gagal',
                                'success' => false,
                                'status' => 'Kelompok anda belum valid!',
                                'data' => null,
                            ];
                        } else {
                            if ($rsSidang == null) {
                                $response = [
                                    'message' => 'Belum dijadwalkan',
                                    'success' => false,
                                    'status' => 'Belum dijadwalkan!',
                                    'data' => $rsSidang,
                                ];
                            } else {
                                // Extract day, date, and time from the "waktu" property
                                $waktuSidang = strtotime($rsSidang->waktu);

                                // Menggunakan strftime untuk mendapatkan nama hari dalam bahasa Indonesia
                                $rsSidang->hari_sidang = strftime('%A', $waktuSidang); // Day

                                // Konversi nama hari ke bahasa Indonesia
                                $rsSidang->hari_sidang = $this->convertDayToIndonesian($rsSidang->hari_sidang);

                                $rsSidang->tanggal_sidang = date('Y-m-d', $waktuSidang); // Date
                                $rsSidang->waktu_sidang = date('H:i:s', $waktuSidang); // Time

                                $rsSidang->kelompok = $kelompok;

                                $response = [
                                    'message' => 'Berhasil',
                                    'success' => true,
                                    'status' => 'Berhasil mendapatkan jadwal sidang!',
                                    'data' => $rsSidang,
                                ];
                            }
                        }
                    }

                } else {
                    $response = [
                        'message' => 'Gagal',
                        'success' => false,
                        'status' => 'Anda belum memiliki kelompok!',
                        'data' => null,
                    ];
                }
            } else {
                $response = [
                    'message' => 'Gagal',
                    'success' => false,
                    'status' => 'Gagal mendapatkan jadwal sidang!',
                    'data' => null,
                ];
            }
        } catch (JWTException $e) {
            $response = [
                'message' => 'Gagal',
                'success' => false,
                'status' => 'Token is Invalid',
                'data' => null,
            ];
        }

        return response()->json($response);
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
}
