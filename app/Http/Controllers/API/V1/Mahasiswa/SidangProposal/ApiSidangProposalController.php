<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\SidangProposal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Api\Mahasiswa\SidangProposal\ApiSidangProposalModel;

class ApiSidangProposalController extends Controller
{

    public function sidangProposalByKelompok(Request $request)
    {
        try {
            $user = $this->getAuthenticatedUser();

            if ($user !== null && $user->user_active == 1) {
                $kelompok = $this->getKelompokMahasiswa($user->user_id);

                if ($kelompok != null) {
                    $response = $this->getSidangProposalByKelompok($kelompok);
                } else {
                    $response = $this->failureResponse('Anda belum memiliki kelompok!');
                }
            } else {
                $response = $this->failureResponse('Gagal mendapatkan jadwal sidang!');
            }
        } catch (JWTException $e) {
            $response = $this->tokenInvalidResponse();
        }

        return response()->json($response);
    }

    private function getAuthenticatedUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    private function getKelompokMahasiswa($userId)
    {
        return ApiSidangProposalModel::pengecekan_kelompok_mahasiswa($userId);
    }

    private function getSidangProposalByKelompok($kelompok)
    {
        $rsSidang = ApiSidangProposalModel::sidangProposalByKelompok($kelompok->id);
        $isSiklusAktif = ApiSidangProposalModel::checkApakahSiklusMasihAktif($kelompok->id_siklus);

        if ($isSiklusAktif == null) {
            $kelompok->id_siklus = 0;

            return [
                'success' => false,
                'status' => 'Siklus capstone sudah tidak aktif!',
                'data' => null,
            ];
        } else {
            if ($kelompok->nomor_kelompok == null) {
                return [
                    'success' => false,
                    'status' => 'Kelompok anda belum valid!',
                    'data' => null,
                ];
            } else {
                if ($rsSidang == null) {
                    return [
                        'success' => false,
                        'status' => 'Belum dijadwalkan!',
                        'data' => $rsSidang,
                    ];
                } else {
                    $response = $this->processSidangData($rsSidang, $kelompok);
                    return $response;
                }
            }
        }
    }

    private function processSidangData($rsSidang, $kelompok)
    {
        $waktuSidang = strtotime($rsSidang->waktu);

        $rsSidang->hari_sidang = strftime('%A', $waktuSidang);
        $rsSidang->hari_sidang = $this->convertDayToIndonesian($rsSidang->hari_sidang);
        $rsSidang->tanggal_sidang = date('Y-m-d', $waktuSidang);
        $rsSidang->waktu_sidang = date('H:i:s', $waktuSidang);

        $rsSidang->kelompok = $kelompok;

        return [
            'success' => true,
            'status' => 'Berhasil mendapatkan jadwal sidang!',
            'data' => $rsSidang,
        ];
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

    private function successResponse($statusMessage, $data)
    {
        return [
            'success' => true,
            'status' => $statusMessage,
            'data' => $data,
        ];
    }

    private function failureResponse($statusMessage, $data = null)
    {
        return [
            'success' => false,
            'status' => $statusMessage,
            'data' => $data,
        ];
    }

    private function tokenInvalidResponse()
    {
        return $this->failureResponse('Token is Invalid', null);
    }
}
