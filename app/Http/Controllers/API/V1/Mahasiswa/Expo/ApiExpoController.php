<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\Expo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Api\Mahasiswa\Expo\ApiExpoModel;

class ApiExpoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($this->validateUser($user)) {
                $kelompok = ApiExpoModel::pengecekan_kelompok_mahasiswa($user->user_id);

                if ($this->validateKelompok($kelompok)) {
                    $rs_expo = ApiExpoModel::getDataExpo($user->user_id);

                    if ($this->validateExpoData($rs_expo)) {
                        $cekStatusExpo = ApiExpoModel::cekStatusExpo($user->user_id);
                        $id_kelompok = ApiExpoModel::idKelompok($user->user_id);
                        $kelengkapanExpo = ApiExpoModel::kelengkapanExpo($user->user_id);

                        $data = [
                            'id_kelompok' => $id_kelompok,
                            'kelompok' => $kelompok,
                            'cekStatusExpo' => $cekStatusExpo,
                            'rs_expo' => $rs_expo,
                            'kelengkapan' => $kelengkapanExpo
                        ];

                        $response = $this->successResponse('Berhasil mendapatkan jadwal expo!', $data);
                    } else {
                        $response = $this->failureResponse('Belum memasuki periode expo!');
                    }
                } else {

                    if ($kelompok != null && $kelompok -> nomor_kelompok == null) {
                        $response = $this->failureResponse('Kelompok anda belum valid!');
                    } else {
                        $response = $this->failureResponse('Anda belum memiliki kelompok!');
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

    public function daftarExpo(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($this->validateUser($user)) {
                $kelompok = ApiExpoModel::pengecekan_kelompok_mahasiswa($user->user_id);

                if ($this->validateKelompok($kelompok)) {
                    $registrationParams = [
                        'id_kelompok' => $kelompok->id,
                        'id_expo' => $request->id_expo,
                        'status' => 'menunggu persetujuan',
                        'created_by' => $user->user_id,
                        'created_date' => now(),
                    ];

                    DB::table('pendaftaran_expo')->updateOrInsert(
                        ['id_kelompok' => $kelompok->id],
                        $registrationParams
                    );

                    $kelompokParams = [
                        'link_berkas_expo' => $request->link_berkas_expo,
                    ];

                    ApiExpoModel::updateKelompokById($kelompok->id, $kelompokParams);

                    $kelompokMHSParams = [
                        'judul_ta_mhs' => $request->judul_ta_mhs,
                    ];
                    ApiExpoModel::updateKelompokMHS($user->user_id, $kelompokMHSParams);

                    $cekStatusExpo = ApiExpoModel::cekStatusExpo($user->user_id);

                    $response = $this->successResponse('Berhasil mendaftarkan expo!', $cekStatusExpo);
                } else {
                    $response = $this->failureResponse('Anda belum memiliki kelompok!');
                }
            } else {
                $response = $this->failureResponse('Gagal mendapatkan data expo!');
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
    private function failureResponse($statusMessage)
    {
        return [
            'success' => false,
            'status' => $statusMessage,
            'data' => null,
        ];
    }
}
