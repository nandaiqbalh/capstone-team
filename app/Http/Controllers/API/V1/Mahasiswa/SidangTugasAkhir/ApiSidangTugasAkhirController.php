<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\SidangTugasAkhir;

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
use App\Models\Api\Mahasiswa\SidangTugasAkhir\ApiSidangTugasAkhirModel;
use App\Models\Api\Mahasiswa\Kelompok\ApiKelompokSayaModel;


class ApiSidangTugasAkhirController extends Controller
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
                $rs_sidang = ApiSidangTugasAkhirModel::getData();
                // data
                $response = [
                    'rs_sidang' => $rs_sidang,
                ];
            } else {
                $response = [
                    'message' => 'Gagal',
                    'success' => false,
                    'status' => 'Gagal mendapatkan data jadwal sidang!',
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

    public function sidangTugasAkhirByMahasiswa(Request $request)
    {
        try {
            // Get the user from the JWT token in the request headers
            $user = JWTAuth::parseToken()->authenticate();

            // Additional checks or actions based on the retrieved user
            if ($user !== null && $user->user_active == 1) {
                // Get data kelompok
                $kelompok = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user->user_id);

                $periodeAvailable = ApiSidangTugasAkhirModel::getPeriodeAvailable();
                $rsSidang = ApiSidangTugasAkhirModel::sidangTugasAkhirByMahasiswa($user->user_id);
                $statusPendaftaran = ApiSidangTugasAkhirModel::getStatusPendaftaran($user->user_id);

                if ($kelompok != null) {
                    // dd($rsSidang);

                    if ($kelompok -> nomor_kelompok == null) {
                        $response = [
                            'message' => 'Gagal',
                            'success' => false,
                            'status' => 'Kelompok belum valid!',
                            'data' => null,
                        ];
                    } else {
                        if ($rsSidang == null) {

                            // BATAS PENDAFTARAN
                            $waktubatas = strtotime($periodeAvailable->tanggal_selesai);

                            $periodeAvailable->hari_batas = strftime('%A', $waktubatas); // Day

                            // Konversi nama hari ke bahasa Indonesia
                            $periodeAvailable->hari_batas = $this->convertDayToIndonesian($periodeAvailable->hari_batas);

                            $periodeAvailable->tanggal_batas = date('Y-m-d', $waktubatas); // Date
                            $periodeAvailable->waktu_batas = date('H:i:s', $waktubatas); // Time


                           if ($statusPendaftaran == null) {
                               $data = [
                                   'kelompok' => $kelompok,
                                   'rsSidang' => $rsSidang,
                                   'periode' => $periodeAvailable,
                                   'status_pendaftaran' => null,
                               ];

                               $response = [
                                'message' => 'Berhasil',
                                'success' => false,
                                'status' => 'Belum mendaftar!',
                                'data' => $data,
                            ];
                           } else {
                               $data = [
                                   'kelompok' => $kelompok,
                                   'rsSidang' => $rsSidang,
                                   'periode' => $periodeAvailable,
                                   'status_pendaftaran' => $statusPendaftaran,
                               ];
                               $response = [
                                'message' => 'Berhasil',
                                'success' => false,
                                'status' => 'Belum dijadwalkan!',
                                'data' => $data,
                            ];

                           }


                       } else {
                           // Extract day, date, and time from the "waktu" property
                           $waktuSidang = strtotime($rsSidang->waktu);

                           $rsSidang->hari_sidang = strftime('%A', $waktuSidang); // Day

                           // Konversi nama hari ke bahasa Indonesia
                           $rsSidang->hari_sidang = $this->convertDayToIndonesian($rsSidang->hari_sidang);

                           $rsSidang->tanggal_sidang = date('Y-m-d', $waktuSidang); // Date
                           $rsSidang->waktu_sidang = date('H:i:s', $waktuSidang); // Time


                           $statusPendaftaran = ApiSidangTugasAkhirModel::getStatusPendaftaran($user->user_id);

                           $data = [
                               'kelompok' => $kelompok,
                               'rsSidang' => $rsSidang,
                               'periode' => $periodeAvailable,
                               'status_pendaftaran' => $statusPendaftaran,
                           ];

                           $response = [
                               'message' => 'Berhasil',
                               'success' => true,
                               'status' => 'Berhasil mendapatkan jadwal sidang!',
                               'data' => $data,
                           ];
                       }
                    }


                } else {
                    $statusPendaftaran = ApiSidangTugasAkhirModel::getStatusPendaftaran($user->user_id);

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

                    $response = [
                        'message' => 'Gagal',
                        'success' => false,
                        'status' => 'Anda belum menyelesaikan capstone!',
                        'data' => $data,
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


    public function daftarSidangTugasAkhir(Request $request)
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
                        'id_mahasiswa' => $user->user_id,
                        'status' => 'menunggu persetujuan',
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
                    ];
                    ApiSidangTugasAkhirModel::updateKelompokMHS($user->user_id, $berkasParams);

                    // cek status pendaftaran
                    $cekStatusPendaftaran = ApiSidangTugasAkhirModel::cekStatusPendaftaranSidangTA($user->user_id);

                    $response = [
                        'message' => 'Berhasil',
                        'success' => true,
                        'status' => 'Berhasil mendaftarkan sidang tugas akhir!',
                        'data' => $cekStatusPendaftaran ->status_pendaftaran,
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
                    'status' => 'Gagal mendapatkan data jadwal sidang!',
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

    public function updateStatusMahasiswaForward()
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiAccountModel::getById($jwtUser->user_id);

            // Check if the user exists and is active
            if ($user && $user->user_active == 1) {
                // Retrieve kelompok data
                try {
                    $kelompok = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user->user_id);

                    if ($kelompok == null) {
                        // User doesn't have a kelompok
                        $response = [
                            'status' => 'Belum mendaftar capstone!',
                            'success' => false,
                            'data' => null,
                        ];
                    } else {
                        // Check if the capstone cycle is still active
                        $isSiklusAktif = ApiKelompokSayaModel::checkApakahSiklusMasihAktif($kelompok->id_siklus);

                        if ($isSiklusAktif == null) {
                            // Capstone cycle is no longer active
                            $kelompok->id_siklus = 0;
                            $response = [
                                'status' => 'Siklus capstone sudah tidak aktif',
                                'success' => false,
                                'data' => null,
                            ];
                        } else {

                            $mahasiswa = ApiSidangTugasAkhirModel::pengecekan_kelompok_mhs($user-> user_id);

                            // Attempt to update kelompok status
                            if ($this->updateStatusForward($mahasiswa)) {
                                // Retrieve updated kelompok data
                                $mahasiswaUpdated = ApiSidangTugasAkhirModel::pengecekan_kelompok_mhs($user-> user_id);

                                // Data response
                                $data = [
                                    'mahasiswa' => $mahasiswaUpdated,
                                ];

                                // Response message
                                $response = [
                                    'status' => 'Berhasil mengubah status mahasiswa!',
                                    'success' => true,
                                    'data' => $data,
                                ];
                            } else {
                                $response = [
                                    'status' => 'Gagal mengubah status mahasiswa. Status saat ini tidak valid untuk operasi ini.',
                                    'success' => false,
                                    'data' => null,
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $response = [
                        'status' => 'Gagal mendapatkan data!',
                        'success' => false,
                        'data' => null,
                    ];
                }
            } else {
                $response = [
                    'status' => 'Pengguna tidak ditemukan atau tidak aktif!',
                    'success' => false,
                    'data' => null,
                ];
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = [
                'status' => 'Gagal mendapatkan data!',
                'success' => false,
                'data' => null,
            ];
        }

        return response()->json($response);
    }

    public function updateStatusMahasiswaBackward()
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiAccountModel::getById($jwtUser->user_id);

            // Check if the user exists and is active
            if ($user && $user->user_active == 1) {
                // Retrieve kelompok data
                try {
                    $kelompok = ApiKelompokSayaModel::pengecekan_kelompok_mahasiswa($user->user_id);

                    if ($kelompok == null) {
                        // User doesn't have a kelompok
                        $response = [
                            'status' => 'Belum mendaftar capstone!',
                            'success' => false,
                            'data' => null,
                        ];
                    } else {
                        // Check if the capstone cycle is still active
                        $isSiklusAktif = ApiKelompokSayaModel::checkApakahSiklusMasihAktif($kelompok->id_siklus);

                        if ($isSiklusAktif == null) {
                            // Capstone cycle is no longer active
                            $kelompok->id_siklus = 0;
                            $response = [
                                'status' => 'Siklus capstone sudah tidak aktif',
                                'success' => false,
                                'data' => null,
                            ];
                        } else {

                            $mahasiswa = ApiSidangTugasAkhirModel::pengecekan_kelompok_mhs($user-> user_id);

                            // Attempt to update kelompok status
                            if ($this->updateStatusBackward($mahasiswa)) {
                                // Retrieve updated kelompok data
                                $mahasiswaUpdated = ApiSidangTugasAkhirModel::pengecekan_kelompok_mhs($user-> user_id);

                                // Data response
                                $data = [
                                    'mahasiswa' => $mahasiswaUpdated,
                                ];

                                // Response message
                                $response = [
                                    'status' => 'Berhasil mengubah status mahasiswa!',
                                    'success' => true,
                                    'data' => $data,
                                ];
                            } else {
                                $response = [
                                    'status' => 'Gagal mengubah status mahasiswa. Status saat ini tidak valid untuk operasi ini.',
                                    'success' => false,
                                    'data' => null,
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $response = [
                        'status' => 'Gagal mendapatkan data!',
                        'success' => false,
                        'data' => null,
                    ];
                }
            } else {
                $response = [
                    'status' => 'Pengguna tidak ditemukan atau tidak aktif!',
                    'success' => false,
                    'data' => null,
                ];
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = [
                'status' => 'Gagal mendapatkan data!',
                'success' => false,
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

    private function updateStatusForward($mahasiswa)
    {
        $statusMapping = [
            'Mengerjakan Capstone' => 'Mengerjakan Laporan Tugas Akhir',
            'Mengerjakan Laporan Tugas Akhir' => 'Laporan Tugas Akhir Disetujui Dosen Pembimbing',
            'Laporan Tugas Akhir Valid' => 'Dalam Proses Peninjauan Pendaftaran Sidang Tugas Akhir',
            'Dijadwalkan Sidang Tugas Akhir' => 'Lulus Sidang Tugas Akhir',
            'Lulus Sidang Tugas Akhir' => 'Laporan Tugas Akhir Valid Setelah Sidang',
        ];

        if (array_key_exists($mahasiswa->status_individu, $statusMapping)) {
            $newStatus = $statusMapping[$mahasiswa->status_individu];

            ApiSidangTugasAkhirModel::updateKelompokMhsById($mahasiswa->id_mahasiswa, ['status_individu' => $newStatus]);
            return true;
        }

        return false;
    }


    private function updateStatusBackward($mahasiswa)
    {
        $statusMapping = [
            'Mengerjakan Laporan Tugas Akhir' => 'Mengerjakan Capstone',
            'Laporan Tugas Akhir Disetujui Dosen Pembimbing' => 'Mengerjakan Laporan Tugas Akhir',
            'Dalam Proses Peninjauan Pendaftaran Sidang Tugas Akhir' => 'Laporan Tugas Akhir Valid',
            'Lulus Sidang Tugas Akhir' => 'Dijadwalkan Sidang Tugas Akhir',
            'Laporan Tugas Akhir Valid Setelah Sidang' => 'Lulus Sidang Tugas Akhir',
        ];

        if (array_key_exists($mahasiswa->status_individu, $statusMapping)) {
            $newStatus = $statusMapping[$mahasiswa->status_individu];

            ApiSidangTugasAkhirModel::updateKelompokMhsById($mahasiswa->id_mahasiswa, ['status_individu' => $newStatus]);
            return true;
        }

        return false;
    }


}
