<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\UploadFile;

use App\Http\Controllers\Controller;
use App\Models\Api\Mahasiswa\UploadFile\ApiUploadFileModel;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Barryvdh\DomPDF\Facade as PDF;

class ApiUploadFileController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiAccountModel::getById($jwtUser->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {
                try {

                    // get data kelompok
                    $file_mhs = ApiUploadFileModel::fileMHS($user->user_id);

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

                        $response = [
                            'message' => 'OK',
                            'status' => 'Berhasil mendapatkan dokumen mahasiswa.',
                            'success' => true,
                            'data' => $data,
                        ];
                    } else {
                        $response = [
                            'status' => 'Kelompok anda belum valid.',
                            'message' => 'Gagal mendapatkan dokumen mahasiswa.',
                            'success' => false,
                            'data' => null,
                        ];
                    }

                } catch (\Exception $e) {
                    // handle unexpected errors
                    $response = [
                        'status' => 'Gagal mendapatkan dokumen mahasiswa.',
                        'message' => 'Pengguna tidak ditemukan!',
                        'success' => false,
                        'data' => null,
                    ];
                }
            } else {
                $response = [
                    'status' => 'Gagal mendapatkan dokumen mahasiswa.',
                    'message' => 'Pengguna tidak ditemukan!',
                    'success' => false,
                    'data' => null,
                ];
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = [
                'status' => 'Gagal. Autentikasi tidak berhasil.',
                'message' => 'Gagal mengambil data!',
                'success' => false,
                'data' => null,
            ];
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

            $user = ApiAccountModel::getById($jwtUser->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {

                // Validate the request
                $validator = Validator::make($request->all(), [
                    'makalah' => 'required|file|mimes:pdf|max:10240',
                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    $response = [
                        'status' => 'Gagal. Validasi dokumen tidak berhasil.',
                        'message' => 'Gagal',
                        'success' => false,
                        'data' => null,
                    ];
                }

                // Upload path
                $uploadPath = '/file/mahasiswa/makalah';

                // Upload
                if ($request->hasFile('makalah')) {
                    $file = $request->file('makalah');

                    // Generate a unique file name
                    $newFileName = 'makalah-' . Str::slug($user->user_name, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Check if the folder exists, if not, create it
                    if (!is_dir(public_path($uploadPath))) {
                        mkdir(public_path($uploadPath), 0755, true);
                    }

                    // Check and delete the existing file
                    $existingFile = ApiUploadFileModel::fileMHS($user ->user_id);

                    if ($existingFile->file_name_makalah) {
                        $filePath = public_path($existingFile->file_path_makalah . '/' . $existingFile->file_name_makalah);

                        if (file_exists($filePath) && !unlink($filePath)) {
                            $response = [
                                'status' => 'Gagal menghapus dokumen lama.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];
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

                        $uploadFile = ApiUploadFileModel::uploadFileMHS($user->user_id, $params);

                        if ($uploadFile) {
                            $response = [
                                'status' => 'Berhasil. Dokumen berhasil diunggah',
                                'message' => 'Berhasil',
                                'success' => true,
                                'data' => $urlMakalah,
                            ];
                        } else {
                            // Return failure response if failed to save new file details
                            $response = [
                                'status' => 'Gagal. Dokumen gagal diunggah.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];
                        }
                    } else {
                        // Return failure response if failed to move the uploaded file
                        $response = [
                            'status' => 'Gagal. Dokumen gagal diunggah.',
                            'message' => 'Gagal',
                            'success' => false,
                            'data' => null,
                        ];
                    }
                } else {
                    $response = [
                        'status' => 'Gagal. Validasi dokumen tidak berhasil.',
                        'message' => 'Dokumen tidak ditemukan!',
                        'success' => false,
                        'data' => null,
                    ];
                }

            } else {
                $response = [
                    'status' => 'Gagal. Pengguna tidak ditemukan.',
                    'message' => 'Pengguna tidak ditemukan!',
                    'success' => false,
                    'data' => null,
                ];
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = [
                'status' => 'Gagal. Autentikasi tidak berhasil!',
                'message' => 'Pengguna tidak ditemukan!',
                'success' => false,
                'data' => null,
            ];
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

            $user = ApiAccountModel::getById($jwtUser->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {

                // Validate the request
                $validator = Validator::make($request->all(), [
                    'laporan_ta' => 'required|file|mimes:pdf|max:10240',
                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    $response = [
                        'status' => 'Gagal. Validasi dokumen tidak berhasil.',
                        'message' => 'Gagal',
                        'success' => false,
                        'data' => null,
                    ];
                }

                // Upload path
                $uploadPath = '/file/mahasiswa/laporan-ta';

                // Upload
                if ($request->hasFile('laporan_ta')) {
                    $file = $request->file('laporan_ta');

                    // Generate a unique file name
                    $newFileName = 'laporan_ta-' . Str::slug($user ->user_name, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Check if the folder exists, if not, create it
                    if (!is_dir(public_path($uploadPath))) {
                        mkdir(public_path($uploadPath), 0755, true);
                    }

                    // Check and delete the existing file
                    $existingFile = ApiUploadFileModel::fileMHS($user ->user_id);

                    if ($existingFile->file_name_laporan_ta) {
                        $filePath = public_path($existingFile->file_path_laporan_ta . '/' . $existingFile->file_name_laporan_ta);

                        if (file_exists($filePath) && !unlink($filePath)) {
                            $response = [
                                'status' => 'Gagal menghapus dokumen lama.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];
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

                        $uploadFile = ApiUploadFileModel::uploadFileMHS($user->user_id, $params);

                        if ($uploadFile) {
                            $response = [
                                'status' => 'Berhasil. Dokumen berhasil diunggah',
                                'message' => 'Berhasil',
                                'success' => true,
                                'data' => $urlDokumen,
                            ];
                        } else {
                            // Return failure response if failed to save new file details
                            $response = [
                                'status' => 'Gagal. Dokumen gagal diunggah.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];
                        }
                    } else {
                        // Return failure response if failed to move the uploaded file
                        $response = [
                            'status' => 'Gagal. Dokumen gagal diunggah.',
                            'message' => 'Gagal',
                            'success' => false,
                            'data' => null,
                        ];
                    }
                }

            } else {
                $response = [
                    'status' => 'Gagal. Pengguna tidak ditemukan.',
                    'message' => 'Pengguna tidak ditemukan!',
                    'success' => false,
                    'data' => null,
                ];
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = [
                'status' => 'Gagal. Autentikasi tidak berhasil!',
                'message' => 'Pengguna tidak ditemukan!',
                'success' => false,
                'data' => null,
            ];
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
}
