<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\Api\Mahasiswa\Dokumen\ApiDokumenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Barryvdh\DomPDF\Facade as PDF;

class ApiDokumenController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiDokumenModel::getById($jwtUser->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {
                try {

                    $kelompok = ApiDokumenModel::pengecekan_kelompok_mahasiswa($user-> user_id);

                    if ($kelompok != null) {

                        if ($kelompok -> nomor_kelompok != null) {
                            $file_mhs = ApiDokumenModel::fileMHS($user->user_id);

                            // data
                            $data = ['file_mhs' => $file_mhs];

                            if ($file_mhs != null) {

                                $file_mhs -> file_url_c100 = $this->getDokumenUrl($file_mhs -> file_path_c100, $file_mhs -> file_name_c100);
                                $file_mhs -> file_url_c200 = $this->getDokumenUrl($file_mhs -> file_path_c200, $file_mhs -> file_name_c200);
                                $file_mhs -> file_url_c300 = $this->getDokumenUrl($file_mhs -> file_path_c300, $file_mhs -> file_name_c300);
                                $file_mhs -> file_url_c400 = $this->getDokumenUrl($file_mhs -> file_path_c400, $file_mhs -> file_name_c400);
                                $file_mhs -> file_url_c500 = $this->getDokumenUrl($file_mhs -> file_path_c500, $file_mhs -> file_name_c500);
                                $file_mhs -> file_url_laporan_ta = $this->getDokumenUrl($file_mhs -> file_path_laporan_ta, $file_mhs -> file_name_laporan_ta);
                                $file_mhs -> file_url_makalah = $this->getDokumenUrl($file_mhs -> file_path_makalah, $file_mhs -> file_name_makalah);

                                return $response = $this->successResponse('Berhasil mendapatkan dokumen mahasiswa.', $data);
                            } else {
                                return $response = $this->failureResponse('Kelompok Anda belum valid!');
                            }
                        } else {
                            return $response = $this->failureResponse('Kelompok Anda belum valid!');
                        }
                    } else {
                        return $response = $this->failureResponse('Anda belum mendaftar capstone!');
                    }

                } catch (\Exception $e) {
                    return $response = $this->failureResponse('Gagal mendapatkan dokumen mahasiswa!');
                }
            } else {
                return $response = $this->failureResponse('Gagal mendapatkan dokumen mahasiswa!');
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $response = $this->failureResponse('Gagal! Autentikasi tidak berhasil!');
        }

        return response()->json($response);
    }


    public function uploadMakalahProcess(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiDokumenModel::getById($jwtUser->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {

                // Validate the request
                $validator = Validator::make($request->all(), [
                    'makalah' => 'required|file|mimes:pdf|max:50240',
                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    return $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');
                }

                // Upload path
                $upload_path = '/../../file/mahasiswa/makalah';

                // Upload
                if ($request->hasFile('makalah')) {
                    // Check and delete the existing file
                    $existingFile = ApiDokumenModel::fileMHS($user ->user_id);

                    if ($existingFile -> file_name_laporan_ta != null) {

                        if ($existingFile->file_status_lta != "Laporan TA Telah Disetujui!") {
                            return $this->failureResponse('Laporan TA belum disetujui kedua dosen pembimbing!');
                        }

                        $file = $request->file('makalah');

                        // Generate a unique file name
                        $new_file_name = 'makalah-' . Str::slug($user->user_name, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                        // Check if the folder exists, if not, create it
                        if (!is_dir(public_path($upload_path))) {
                            mkdir(public_path($upload_path), 0755, true);
                        }

                        if ($existingFile->file_name_makalah) {
                            $filePath = public_path($existingFile->file_path_makalah . '/' . $existingFile->file_name_makalah);

                            if (file_exists($filePath) && !unlink($filePath)) {
                                return $response = $this->failureResponse('Gagal menghapus dokumen lama!');
                            }
                        }

                        // Move the uploaded file to the specified path
                        if ($file->move(public_path($upload_path), $new_file_name)) {
                            // Save the new file details in the database
                            $urlMakalah = url($upload_path . '/' . $new_file_name);

                            $params = [
                                'file_name_makalah' => $new_file_name,
                                'file_path_makalah' => $upload_path,
                                'file_status_mta' => 'Menunggu Persetujuan Makalah TA!',
                                'file_status_mta_dosbing1' => 'Menunggu Persetujuan Makalah TA!',
                                'file_status_mta_dosbing2' => 'Menunggu Persetujuan Makalah TA!',
                                'status_individu' => 'Menunggu Persetujuan Makalah TA!',

                            ];

                            $uploadFile = ApiDokumenModel::uploadFileMHS($user->user_id, $params);

                            if ($uploadFile) {
                                return $response = $this->successResponse('Berhasil! Dokumen berhasil diunggah!', $urlMakalah);
                            } else {
                                return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                            }
                    } else {
                        return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                    }
                    } else{
                        return $response = $this->failureResponse('Lengkapi terlebih dahulu laporan Tugas Akhir!');

                    }

                } else {
                    return $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');
                }

            } else {
                return $response = $this->failureResponse('Gagal! Pengguna tidak ditemukan!');
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $response = $this->failureResponse('Gagal! Autentikasi tidak berhasil!');
        }

        return response()->json($response);
    }

    public function uploadLaporanProcess(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiDokumenModel::getById($jwtUser->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {

                // Validate the request
                $validator = Validator::make($request->all(), [
                    'laporan_ta' => 'required|file|mimes:pdf|max:50240',
                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    return $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');

                }

                // Upload path
                $upload_path = '/../../file/mahasiswa/laporan-ta';

                $kelompok = ApiDokumenModel::pengecekan_kelompok_mahasiswa($user-> user_id);
                // Check and delete the existing file
                $dokumenKelompok = ApiDokumenModel::getKelompokFile($kelompok->id_kelompok);

                // Upload
                if ($request->hasFile('laporan_ta')) {

                    if ($dokumenKelompok -> file_name_c500 != null) {
                        $file = $request->file('laporan_ta');

                        // Generate a unique file name
                        $new_file_name = 'laporan_ta-' . Str::slug($user ->user_name, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                        // Check if the folder exists, if not, create it
                        if (!is_dir(public_path($upload_path))) {
                            mkdir(public_path($upload_path), 0755, true);
                        }

                        // Check and delete the existing file
                        $existingFile = ApiDokumenModel::fileMHS($user ->user_id);

                        if ($existingFile->file_name_laporan_ta) {
                            $filePath = public_path($existingFile->file_path_laporan_ta . '/' . $existingFile->file_name_laporan_ta);

                            if (file_exists($filePath) && !unlink($filePath)) {
                                return $response = $this->failureResponse('Gagal menghapus dokumen lama!');
                            }
                        }

                        // Move the uploaded file to the specified path
                        if ($file->move(public_path($upload_path), $new_file_name)) {
                            // Save the new file details in the database
                            $urlDokumen = url($upload_path . '/' . $new_file_name);

                            $isMahasiswaSidangTA = ApiDokumenModel::isMahasiswaSidangTA($existingFile->id_kel_mhs);

                            if ($isMahasiswaSidangTA) {
                                $params = [
                                    'file_name_laporan_ta' => $new_file_name,
                                    'file_path_laporan_ta' => $upload_path,
                                    'file_status_lta' => 'Menunggu Persetujuan Final Laporan TA!',
                                    'file_status_lta_dosbing1' => 'Menunggu Persetujuan Final Laporan TA!',
                                    'file_status_lta_dosbing2' => 'Menunggu Persetujuan Final Laporan TA!',
                                    'status_individu' => 'Menunggu Persetujuan Final Laporan TA!',
                                ];
                            } else {
                                $params = [
                                    'file_name_laporan_ta' => $new_file_name,
                                    'file_path_laporan_ta' => $upload_path,
                                    'file_status_lta' => 'Menunggu Persetujuan Laporan TA!',
                                    'file_status_lta_dosbing1' => 'Menunggu Persetujuan Laporan TA!',
                                    'file_status_lta_dosbing2' => 'Menunggu Persetujuan Laporan TA!',
                                    'status_individu' => 'Menunggu Persetujuan Laporan TA!',
                                ];

                            }

                            $uploadFile = ApiDokumenModel::uploadFileMHS($user->user_id, $params);

                            if ($uploadFile) {
                                return $response = $this->successResponse('Berhasil! Dokumen berhasil diunggah!', $urlDokumen);
                            } else {
                                return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                            }
                        } else {
                            return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                        }
                    } else{
                        return $response = $this->failureResponse('Lengkapi terlebih dahulu dokumen capstone!');
                    }

                } else {
                    return $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');
                }

            } else {
                return $response = $this->failureResponse('Gagal! Pengguna tidak ditemukan!');
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $response = $this->failureResponse('Gagal! Autentikasi tidak berhasil!');
        }

        return response()->json($response);
    }

    private function getDokumenUrl($path, $name)
    {
        if (!empty($name)) {
            $dokumenUrl = url($path . '/'. $name);
        } else {
            $dokumenUrl = null;
        }

        return $dokumenUrl;
    }

    private function successResponse($statusMessage, $data)
    {
        return [
            'success' => true,
            'status' => $statusMessage,
            'data' => $data,
        ];
    }

    private function failureResponse($statusMessage)
    {
        return [
            'success' => false,
            'status' => $statusMessage,
            'data' => null,
        ];
    }
}
