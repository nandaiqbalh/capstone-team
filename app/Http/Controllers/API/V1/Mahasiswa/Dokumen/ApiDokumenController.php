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

                    // get data kelompok
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

                        $response = $this->successResponse('Berhasil mendapatkan dokumen mahasiswa.', $data);
                    } else {
                        $response = $this->failureResponse('Kelompok Anda belum valid!');
                    }
                } catch (\Exception $e) {
                    $response = $this->failureResponse('Gagal mendapatkan dokumen mahasiswa!');
                }
            } else {
                $response = $this->failureResponse('Gagal mendapatkan dokumen mahasiswa!');
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = $this->failureResponse('Gagal! Autentikasi tidak berhasil!');
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
                    'makalah' => 'required|file|mimes:pdf|max:10240',
                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');
                }

                // Upload path
                $uploadPath = '/file/mahasiswa/makalah';

                // Upload
                if ($request->hasFile('makalah')) {
                    // Check and delete the existing file
                    $existingFile = ApiDokumenModel::fileMHS($user ->user_id);

                    if ($existingFile -> file_name_laporan_ta != null) {

                    $file = $request->file('makalah');

                    // Generate a unique file name
                    $newFileName = 'makalah-' . Str::slug($user->user_name, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Check if the folder exists, if not, create it
                    if (!is_dir(public_path($uploadPath))) {
                        mkdir(public_path($uploadPath), 0755, true);
                    }



                    if ($existingFile->file_name_makalah) {
                        $filePath = public_path($existingFile->file_path_makalah . '/' . $existingFile->file_name_makalah);

                        if (file_exists($filePath) && !unlink($filePath)) {
                            $response = $this->failureResponse('Gagal menghapus dokumen lama!');
                        }
                    }

                    // Move the uploaded file to the specified path
                    if ($file->move(public_path($uploadPath), $newFileName)) {
                        // Save the new file details in the database
                        $urlMakalah = url($uploadPath . '/' . $newFileName);

                        $params = [
                            'file_name_makalah' => $newFileName,
                            'file_path_makalah' => $uploadPath,
                        ];

                        $uploadFile = ApiDokumenModel::uploadFileMHS($user->user_id, $params);

                        if ($uploadFile) {
                            $response = $this->successResponse('Berhasil! Dokumen berhasil diunggah!', $urlMakalah);
                            $statusParam = [
                                'status_individu' => 'Mengunggah Laporan TA',
                            ];
                            ApiDokumenModel::uploadFileMHS($user->user_id, $statusParam);
                        } else {
                            $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                        }
                    } else {
                        $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                    }
                    } else{
                        $response = $this->failureResponse('Lengkapi terlebih dahulu laporan Tugas Akhir!');

                    }

                } else {
                    $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');
                }

            } else {
                $response = $this->failureResponse('Gagal! Pengguna tidak ditemukan!');
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = $this->failureResponse('Gagal! Autentikasi tidak berhasil!');
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
                    'laporan_ta' => 'required|file|mimes:pdf|max:10240',
                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');

                }

                // Upload path
                $uploadPath = '/file/mahasiswa/laporan-ta';

                $kelompok = ApiDokumenModel::pengecekan_kelompok_mahasiswa($user-> user_id);

                // Check and delete the existing file
                $dokumenKelompok = ApiDokumenModel::getKelompokFile($$kelompok->id_kelompok);

                // Upload
                if ($request->hasFile('laporan_ta')) {

                    if ($dokumenKelompok -> file_name_c500 != null) {
                        $file = $request->file('laporan_ta');

                        // Generate a unique file name
                        $newFileName = 'laporan_ta-' . Str::slug($user ->user_name, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                        // Check if the folder exists, if not, create it
                        if (!is_dir(public_path($uploadPath))) {
                            mkdir(public_path($uploadPath), 0755, true);
                        }

                        // Check and delete the existing file
                        $existingFile = ApiDokumenModel::fileMHS($user ->user_id);

                        if ($existingFile->file_name_laporan_ta) {
                            $filePath = public_path($existingFile->file_path_laporan_ta . '/' . $existingFile->file_name_laporan_ta);

                            if (file_exists($filePath) && !unlink($filePath)) {
                                $response = $this->failureResponse('Gagal menghapus dokumen lama!');
                            }
                        }

                        // Move the uploaded file to the specified path
                        if ($file->move(public_path($uploadPath), $newFileName)) {
                            // Save the new file details in the database
                            $urlDokumen = url($uploadPath . '/' . $newFileName);

                            $params = [
                                'file_name_laporan_ta' => $newFileName,
                                'file_path_laporan_ta' => $uploadPath,
                            ];

                            $uploadFile = ApiDokumenModel::uploadFileMHS($user->user_id, $params);

                            if ($uploadFile) {
                                $response = $this->successResponse('Berhasil! Dokumen berhasil diunggah!', $urlDokumen);
                            } else {
                                $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                            }
                        } else {
                            $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                        }
                    } else{
                        $response = $this->failureResponse('Lengkapi terlebih dahulu dokumen capstone!');
                    }

                } else {
                    $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');
                }

            } else {
                $response = $this->failureResponse('Gagal! Pengguna tidak ditemukan!');
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = $this->failureResponse('Gagal! Autentikasi tidak berhasil!');
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
