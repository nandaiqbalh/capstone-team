<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\Expo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Api\Mahasiswa\Expo\ApiExpoModel;
use App\Models\Api\Mahasiswa\Kelompok\ApiKelompokSayaModel;

class ApiExpoController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Get the user from the JWT token in the request headers
            $user = JWTAuth::parseToken()->authenticate();

            // Additional checks or actions based on the retrieved user
            if ($user != null && $user->user_active == 1) {

                $kelompok = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user->user_id);

                if ($kelompok != null) {
                    $rs_expo = ApiExpoModel::getDataExpo($user->user_id);

                    // check apakah sudah ada jadwal expo?
                    if ($rs_expo != null) {

                        // check status expo
                        $cekStatusExpo = ApiExpoModel::cekStatusExpo($user->user_id);

                        $id_kelompok = ApiExpoModel::idKelompok($user->user_id);

                        // get data expo
                        $kelengkapanExpo = ApiExpoModel::kelengkapanExpo($user->user_id);

                        // data
                        $data = [
                            'id_kelompok' => $id_kelompok,
                            'kelompok' => $kelompok,
                            'cekStatusExpo' => $cekStatusExpo,
                            'rs_expo' => $rs_expo,
                            'kelengkapan' => $kelengkapanExpo
                        ];

                        if ($kelompok->nomor_kelompok == null) {
                            $response = [
                                'message' => 'Gagal',
                                'success' => false,
                                'status' => 'Kelompok anda belum valid!',
                                'data' => null,
                            ];
                        } else {

                            $response = [
                                'message' => 'Berhasil',
                                'success' => true,
                                'status' => 'Berhasil mendapatkan jadwal expo!',
                                'data' => $data,
                            ];
                        }

                    } else {
                        $response = [
                            'message' => 'Gagal',
                            'success' => false,
                            'status' => 'Belum ada jadwal expo!',
                            'data' => null,
                        ];
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
                    'status' => 'Pengguna tidak ditemukan!',
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

    public function daftarExpo(Request $request)
{
    try {
        // Get the user from the JWT token in the request headers
        $user = JWTAuth::parseToken()->authenticate();

        // Additional checks or actions based on the retrieved user
        if ($user != null && $user->user_active == 1) {

            // Check if the user belongs to a group
            $kelompok = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user->user_id);

            if ($kelompok != null) {
                // Registration parameters
                $registrationParams = [
                    'id_kelompok' => $kelompok->id,
                    'id_expo' => $request->id_expo, // corrected typo: changed $reqeust to $request
                    'status' => 'menunggu persetujuan',
                    'created_by' => $user->user_id,
                    'created_date' => now(), // Use Laravel helper function for the current date and time
                ];

                 // Use updateOrInsert to handle both insertion and updating
                DB::table('pendaftaran_expo')->updateOrInsert(
                    ['id_kelompok' => $kelompok->id], // The condition to check if the record already exists
                    $registrationParams // The data to be updated or inserted
                );

                // Update group link_berkas_expo
                $kelompokParams = [
                    'link_berkas_expo' => $request->link_berkas_expo,
                ];
                ApiKelompokSayaModel::updateKelompokById($kelompok->id, $kelompokParams);

                // Update student's title
                $kelompokMHSParams = [
                    'judul_ta_mhs' => $request->judul_ta_mhs,
                ];
                ApiExpoModel::updateKelompokMHS($user->user_id, $kelompokMHSParams);

                // Check Expo registration
                $cekStatusExpo = ApiExpoModel::cekStatusExpo($user->user_id);

                $response = [
                    'message' => 'Berhasil',
                    'success' => true,
                    'status' => 'Berhasil mendaftarkan expo!',
                    'data' => $cekStatusExpo,
                ];

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
                'status' => 'Gagal mendapatkan data expo!',
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
}
