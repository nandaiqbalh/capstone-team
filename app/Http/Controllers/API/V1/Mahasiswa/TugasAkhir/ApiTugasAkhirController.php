<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\TugasAkhir;

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
use App\Models\Api\Mahasiswa\TugasAkhir\ApiTugasAkhirModel;


class ApiTugasAkhirController extends Controller
{
    public function sidangTugasAkhirByMahasiswa(Request $request)
    {
        try {
            // Get the user from the JWT token in the request headers
            $user = JWTAuth::parseToken()->authenticate();

            // Additional checks or actions based on the retrieved user
            if ($user !== null && $user->user_active == 1) {
                // Get data kelompok
                $kelompok = ApiTugasAkhirModel::pengecekan_kelompok_mahasiswa($user->user_id);

                $periodeAvailable = ApiTugasAkhirModel::getPeriodeAvailable();
                $rsSidang = ApiTugasAkhirModel::sidangTugasAkhirByMahasiswa($user->user_id);
                $statusPendaftaran = ApiTugasAkhirModel::getStatusPendaftaran($user->user_id);

                if ($kelompok != null ) {
                    // dd($rsSidang);

                    if ($kelompok -> nomor_kelompok == null) {
                        $response = $this->failureResponse('Anda belum menyelesaikan capstone!');
                    } else {
                        if ($rsSidang == null ) {

                            if ($periodeAvailable != null) {
                                // BATAS PENDAFTARAN
                                $waktubatas = strtotime($periodeAvailable->tanggal_selesai);

                                $periodeAvailable->hari_batas = strftime('%A', $waktubatas); // Day

                                // Konversi nama hari ke bahasa Indonesia
                                $periodeAvailable->hari_batas = $this->convertDayToIndonesian($periodeAvailable->hari_batas);

                                $periodeAvailable->tanggal_batas = date('d-m-Y', $waktubatas); // Date
                                $periodeAvailable->waktu_batas = date('H:i:s', $waktubatas); // Time

                            }


                            if ($statusPendaftaran == null) {
                                $data = [
                                    'kelompok' => $kelompok,
                                    'rsSidang' => $rsSidang,
                                    'periode' => $periodeAvailable,
                                    'status_pendaftaran' => null,
                                ];

                                $response = $this->failureResponse('Belum mendaftar sidang Tugas Akhir!', $data);

                            } else {
                                $data = [
                                    'kelompok' => $kelompok,
                                    'rsSidang' => $rsSidang,
                                    'periode' => $periodeAvailable,
                                    'status_pendaftaran' => $statusPendaftaran,
                                ];

                                $response = $this->failureResponse('Belum dijadwalkan untuk sidang Tugas Akhir!', $data);

                            }


                        } else {

                            if ($periodeAvailable != null) {
                                // Extract day, date, and time from the "waktu" property
                                $waktuSidang = strtotime($rsSidang->waktu);

                                $rsSidang->hari_sidang = strftime('%A', $waktuSidang); // Day

                                // Konversi nama hari ke bahasa Indonesia
                                $rsSidang->hari_sidang = $this->convertDayToIndonesian($rsSidang->hari_sidang);

                                $rsSidang->tanggal_sidang = date('d-m-Y', $waktuSidang); // Date
                                $rsSidang->waktu_sidang = date('H:i:s', $waktuSidang); // Time
                            }

                            $statusPendaftaran = ApiTugasAkhirModel::getStatusPendaftaran($user->user_id);

                            $data = [
                                'kelompok' => $kelompok,
                                'rsSidang' => $rsSidang,
                                'periode' => $periodeAvailable,
                                'status_pendaftaran' => $statusPendaftaran,
                            ];

                            $response = $this->successResponse('Berhasil mendapatkan jadwal sidang Tugas Akhir!', $data);

                        }
                    }


                } else {
                    $statusPendaftaran = ApiTugasAkhirModel::getStatusPendaftaran($user->user_id);

                    if ($statusPendaftaran == null) {
                        $data = [
                            'kelompok' => $kelompok,
                            'rsSidang' => $rsSidang,
                            'periode' => $periodeAvailable,
                            'status_pendaftaran' => null,
                        ];
                    } else {
                        $data = [
                            'kelompok' => $kelompok,
                            'rsSidang' => $rsSidang,
                            'periode' => $periodeAvailable,
                            'status_pendaftaran' => $statusPendaftaran,
                        ];
                    }
                    $response = $this->failureResponse('Anda belum menyelesaikan capstone!');
                }
            } else {
                $response = $this->failureResponse('Gagal mendapatkan jadwal sidang Tugas Akhir!');
            }
        } catch (JWTException $e) {
            $response = $this->failureResponse('Token is Invalid');
        }

        return response()->json($response);
    }


    public function daftarSidangTugasAkhir(Request $request)
    {
        try {
            // Get the user from the JWT token in the request headers
            $user = JWTAuth::parseToken()->authenticate();

            // Additional checks or actions based on the retrieved user
            if ($user != null && $user->user_active == 1) {

                // Check if the user belongs to a group
                $kelompok = ApiTugasAkhirModel::pengecekan_kelompok_mahasiswa($user->user_id);

                if ($kelompok != null) {
                    $periodeAvailable = ApiTugasAkhirModel::getPeriodeAvailable();

                     // Check and delete the existing file
                     $dokumen_mahasiwa = ApiTugasAkhirModel::fileMHS($user ->user_id);
                    if ($kelompok-> file_name_c500 != null && $dokumen_mahasiwa -> file_name_laporan_ta != null && $dokumen_mahasiwa -> file_name_makalah != null) {
                        // Registration parameters
                        if ($kelompok -> status_expo == "Lulus Expo Project!") {
                            $registrationParams = [
                                'id_mahasiswa' => $user->user_id,
                                'id_periode' => $periodeAvailable ->id,
                                'status' => 'Menunggu Validasi Jadwal!',
                                'created_by' => $user->user_id,
                                'created_date' => now(), // Use Laravel helper function for the current date and time
                            ];

                            // Use updateOrInsert to handle both insertion and updating
                            DB::table('pendaftaran_sidang_ta')->updateOrInsert(
                                ['id_mahasiswa' => $user->user_id], // The condition to check if the record already exists
                                $registrationParams // The data to be updated or inserted
                            );

                            // Update kelompok mhs
                            $berkasParams = [
                                'link_upload' => $request->link_upload,
                                'judul_ta_mhs' => $request->judul_ta_mhs,
                                'status_individu' => 'Menunggu Validasi Jadwal!',
                            ];
                            ApiTugasAkhirModel::updateKelompokMHS($user->user_id, $berkasParams);

                            // cek status pendaftaran
                            $cekStatusPendaftaran = ApiTugasAkhirModel::cekStatusPendaftaranSidangTA($user->user_id);

                            $response = $this->successResponse('Berhasil mendaftarkan sidang Tugas Akhir!', $cekStatusPendaftaran ->status_pendaftaran);

                        } else {
                            $response = $this->failureResponse('Anda harus lulus expo terlebih dahulu!');
                        }
                    } else {
                        $response = $this->failureResponse('Lengkapi terlebih dahulu dokumen Anda!');
                    }
                } else {
                    $response = $this->failureResponse('Anda belum menyelesaikan capstone!');
                }
            } else {
                $response = $this->failureResponse('Gagal mendapatkan jadwal sidang Tugas Akhir!');
            }
        } catch (JWTException $e) {
            $response = $this->failureResponse('Token is Invalid');
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

}
