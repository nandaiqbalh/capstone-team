<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Dokumen;

use App\Http\Controllers\Controller;
use App\Models\Api\Mahasiswa\Dokumen\ApiDokumenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JWTAuth;

class ApiDokumenCapstoneController extends Controller
{
    public function uploadC100Process(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiDokumenModel::getById($jwtUser->user_id);

            $kelompok = ApiDokumenModel::pengecekan_kelompok_mahasiswa($user->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {

                // Validate the request
                $validator = Validator::make($request->all(), [
                    'c100' => 'required|file|mimes:pdf|max:50240',
                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    return $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');

                }

                // Upload path
                $upload_path = '/../../file/kelompok/c100';
                // Check and delete the existing file
                $id_kelompok = $kelompok->id_kelompok;

                // Check and delete the existing file
                $existingFile = ApiDokumenModel::getKelompokFile($id_kelompok);

                if ($existingFile->is_lulus_expo == 1) {
                    return $response = $this->failureResponse('Kelompok Anda sudah lulus Expo Project!');
                }
                // get siklus kelompok
                $siklus = ApiDokumenModel::getSiklusKelompok($existingFile->id_siklus);

                if ($siklus != null) {
                    if ($request->hasFile('c100') && $existingFile != null) {
                        $file = $request->file('c100');

                        // Generate a unique file name
                        $newFileName = 'c100-' . Str::slug($existingFile->nomor_kelompok, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                        // Check if the folder exists, if not, create it
                        if (!is_dir(public_path($upload_path))) {
                            mkdir(public_path($upload_path), 0755, true);
                        }

                        if ($existingFile->file_name_c100) {
                            $filePath = public_path($existingFile->file_path_c100 . '/' . $existingFile->file_name_c100);

                            if (file_exists($filePath) && !unlink($filePath)) {
                                return $response = $this->failureResponse('Gagal menghapus dokumen lama!');
                            }
                        }

                        // Move the uploaded file to the specified path
                        if ($file->move(public_path($upload_path), $newFileName)) {
                            // Save the new file details in the database
                            $urlc100 = url($upload_path . '/' . $newFileName);

                            $params = [
                                'file_name_c100' => $newFileName,
                                'file_path_c100' => $upload_path,
                            ];

                            $uploadFile = ApiDokumenModel::uploadFileKel($id_kelompok, $params);

                            if ($uploadFile) {

                                $isKelompokSidang = ApiDokumenModel::isKelompokSidang($id_kelompok);

                                if ($isKelompokSidang) {
                                    $statusParam = [
                                        'status_kelompok' => 'Menunggu Persetujuan Final C100',
                                        'file_status_c100' => 'Menunggu Persetujuan Final C100',
                                        'file_status_c100_dosbing1' => 'Menunggu Persetujuan Final C100',
                                        'file_status_c100_dosbing2' => 'Menunggu Persetujuan Final C100',
                                        'status_dosen_pembimbing_1' => 'Menunggu Persetujuan Final C100',
                                        'status_dosen_pembimbing_2' => 'Menunggu Persetujuan Final C100',
                                    ];
                                } else {
                                    $statusParam = [
                                        'status_kelompok' => 'Menunggu Persetujuan C100',
                                        'file_status_c100' => 'Menunggu Persetujuan C100',
                                        'file_status_c100_dosbing1' => 'Menunggu Persetujuan C100',
                                        'file_status_c100_dosbing2' => 'Menunggu Persetujuan C100',
                                        'status_dosen_pembimbing_1' => 'Menunggu Persetujuan C100',
                                        'status_dosen_pembimbing_2' => 'Menunggu Persetujuan C100',
                                    ];

                                }

                                $upload = ApiDokumenModel::uploadFileKel($id_kelompok, $statusParam);

                                if (!$upload) {
                                    return $response = $this->successResponse('Berhasil! Dokumen berhasil diunggah!', $urlc100);

                                } else {
                                    return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');

                                }

                            } else {
                                return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                            }
                        } else {
                            return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                        }
                    } else {
                        return $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');
                    }
                } else {
                    return $response = $this->failureResponse('Gagal! Sudah melewati batas waktu unggah dokumen C100!');
                }
            } else {
                return $response = $this->failureResponse('Gagal! Pengguna tidak ditemukan!');
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $response = $this->failureResponse('Gagal! Autentikasi tidak berhasil!');
        }
        return response()->json($response);
    }

    public function uploadC200Process(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiDokumenModel::getById($jwtUser->user_id);

            $kelompok = ApiDokumenModel::pengecekan_kelompok_mahasiswa($user->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {

                // Validate the request
                $validator = Validator::make($request->all(), [
                    'c200' => 'required|file|mimes:pdf|max:50240',
                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    return $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');

                }

                // Upload path
                $upload_path = '/../../file/kelompok/c200';
                // Check and delete the existing file
                $id_kelompok = $kelompok->id_kelompok;

                // Check and delete the existing file
                $existingFile = ApiDokumenModel::getKelompokFile($id_kelompok);

                if ($existingFile->is_lulus_expo == 1) {
                    return $response = $this->failureResponse('Kelompok Anda sudah lulus Expo Project!');
                }

                if ($existingFile->file_status_c100 != "Final C100 Telah Disetujui") {
                    return $response = $this->failureResponse('Gagal mengunggah! C100 belum disetujui kedua dosen pembimbing!');
                }

                if ($existingFile->file_status_c200 == "C200 Telah Disetujui") {
                    return $response = $this->failureResponse('Gagal mengunggah! C200 telah disetujui kedua dosen pembimbing!');
                }

                if ($request->hasFile('c200') && $existingFile != null) {
                    if ($existingFile->file_name_c100 != null) {
                        $file = $request->file('c200');
                        // Generate a unique file name
                        $newFileName = 'c200-' . Str::slug($existingFile->nomor_kelompok, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                        // Check if the folder exists, if not, create it
                        if (!is_dir(public_path($upload_path))) {
                            mkdir(public_path($upload_path), 0755, true);
                        }

                        if ($existingFile->file_name_c200) {
                            $filePath = public_path($existingFile->file_path_c200 . '/' . $existingFile->file_name_c200);
                            if (file_exists($filePath) && !unlink($filePath)) {
                                return $response = $this->failureResponse('Gagal menghapus dokumen lama!');
                            }
                        }

                        // Move the uploaded file to the specified path
                        if ($file->move(public_path($upload_path), $newFileName)) {
                            // Save the new file details in the database
                            $urlc200 = url($upload_path . '/' . $newFileName);

                            $params = [
                                'file_name_c200' => $newFileName,
                                'file_path_c200' => $upload_path,
                            ];

                            $uploadFile = ApiDokumenModel::uploadFileKel($id_kelompok, $params);

                            if ($uploadFile) {
                                return $response = $this->successResponse('Berhasil! Dokumen berhasil diunggah!', $urlc200);
                                $statusParam = [
                                    'status_kelompok' => 'Menunggu Persetujuan C200',
                                    'file_status_c200' => 'Menunggu Persetujuan C200',
                                    'file_status_c200_dosbing1' => 'Menunggu Persetujuan C200',
                                    'file_status_c200_dosbing2' => 'Menunggu Persetujuan C200',
                                    'status_dosen_pembimbing_1' => 'Menunggu Persetujuan C200',
                                    'status_dosen_pembimbing_2' => 'Menunggu Persetujuan C200',
                                ];
                                ApiDokumenModel::uploadFileKel($id_kelompok, $statusParam);

                            } else {
                                return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                            }
                        } else {
                            return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                        }
                    } else {
                        return $response = $this->failureResponse('Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C100!');
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

    public function uploadC300Process(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiDokumenModel::getById($jwtUser->user_id);

            $kelompok = ApiDokumenModel::pengecekan_kelompok_mahasiswa($user->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {

                // Validate the request
                $validator = Validator::make($request->all(), [
                    'c300' => 'required|file|mimes:pdf|max:50240',
                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    return $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');
                }

                // Upload path
                $upload_path = '/../../file/kelompok/c300';
                // Check and delete the existing file
                $id_kelompok = $kelompok->id_kelompok;

                // Check and delete the existing file
                $existingFile = ApiDokumenModel::getKelompokFile($id_kelompok);

                if ($existingFile->is_lulus_expo == 1) {
                    return $response = $this->failureResponse('Kelompok Anda sudah lulus Expo Project!');
                }

                if ($existingFile->file_status_c200 != "C200 Telah Disetujui") {
                    return $response = $this->failureResponse('Gagal mengunggah! C200 belum disetujui kedua dosen pembimbing!');
                }

                if ($existingFile->file_status_c300 == "C300 Telah Disetujui") {
                    return $response = $this->failureResponse('Gagal mengunggah! C300 telah disetujui kedua dosen pembimbing!');
                }

                if ($request->hasFile('c300') && $existingFile != null) {
                    if ($existingFile->file_name_c200 != null) {
                        $file = $request->file('c300');
                        // Generate a unique file name
                        $newFileName = 'c300-' . Str::slug($existingFile->nomor_kelompok, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                        // Check if the folder exists, if not, create it
                        if (!is_dir(public_path($upload_path))) {
                            mkdir(public_path($upload_path), 0755, true);
                        }

                        if ($existingFile->file_name_c300) {
                            $filePath = public_path($existingFile->file_path_c300 . '/' . $existingFile->file_name_c300);
                            if (file_exists($filePath) && !unlink($filePath)) {
                                return $response = $this->failureResponse('Gagal menghapus dokumen lama!');
                            }
                        }

                        // Move the uploaded file to the specified path
                        if ($file->move(public_path($upload_path), $newFileName)) {
                            // Save the new file details in the database
                            $urlc300 = url($upload_path . '/' . $newFileName);

                            $params = [
                                'file_name_c300' => $newFileName,
                                'file_path_c300' => $upload_path,
                            ];

                            $uploadFile = ApiDokumenModel::uploadFileKel($id_kelompok, $params);

                            if ($uploadFile) {
                                return $response = $this->successResponse('Berhasil! Dokumen berhasil diunggah!', $urlc300);
                                $statusParam = [
                                    'status_kelompok' => 'Menunggu Persetujuan C300',
                                    'file_status_c300' => 'Menunggu Persetujuan C300',
                                    'file_status_c300_dosbing1' => 'Menunggu Persetujuan C300',
                                    'file_status_c300_dosbing2' => 'Menunggu Persetujuan C300',
                                    'status_dosen_pembimbing_1' => 'Menunggu Persetujuan C300',
                                    'status_dosen_pembimbing_2' => 'Menunggu Persetujuan C300',
                                ];
                                ApiDokumenModel::uploadFileKel($id_kelompok, $statusParam);

                            } else {
                                return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                            }
                        } else {
                            return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                        }
                    } else {
                        return $response = $this->failureResponse('Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C200!');
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
    public function uploadC400Process(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiDokumenModel::getById($jwtUser->user_id);

            $kelompok = ApiDokumenModel::pengecekan_kelompok_mahasiswa($user->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {

                // Validate the request
                $validator = Validator::make($request->all(), [
                    'c400' => 'required|file|mimes:pdf|max:50240',

                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    return $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');

                }

                // Upload path
                $upload_path = '/../../file/kelompok/c400';
                // Check and delete the existing file
                $id_kelompok = $kelompok->id_kelompok;

                // Check and delete the existing file
                $existingFile = ApiDokumenModel::getKelompokFile($id_kelompok);

                if ($existingFile->is_lulus_expo == 1) {
                    return $response = $this->failureResponse('Kelompok Anda sudah lulus Expo Project!');
                }

                if ($existingFile->file_status_c300 != "C300 Telah Disetujui") {
                    return $response = $this->failureResponse('Gagal mengunggah! C300 belum disetujui kedua dosen pembimbing!');
                }

                if ($existingFile->file_status_c400 == "C400 Telah Disetujui") {
                    return $response = $this->failureResponse('Gagal mengunggah! C400 telah disetujui kedua dosen pembimbing!');
                }

                if ($request->hasFile('c400') && $existingFile != null) {
                    if ($existingFile->file_name_c300 != null) {

                        $file = $request->file('c400');
                        // Generate a unique file name
                        $newFileName = 'c400-' . Str::slug($existingFile->nomor_kelompok, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                        // Check if the folder exists, if not, create it
                        if (!is_dir(public_path($upload_path))) {
                            mkdir(public_path($upload_path), 0755, true);
                        }

                        if ($existingFile->file_name_c400) {
                            $filePath = public_path($existingFile->file_path_c400 . '/' . $existingFile->file_name_c400);
                            if (file_exists($filePath) && !unlink($filePath)) {
                                return $response = $this->failureResponse('Gagal menghapus dokumen lama!');
                            }
                        }

                        // Move the uploaded file to the specified path
                        if ($file->move(public_path($upload_path), $newFileName)) {
                            // Save the new file details in the database
                            $urlc400 = url($upload_path . '/' . $newFileName);

                            $params = [
                                'file_name_c400' => $newFileName,
                                'file_path_c400' => $upload_path,
                            ];

                            $uploadFile = ApiDokumenModel::uploadFileKel($id_kelompok, $params);

                            if ($uploadFile) {
                                return $response = $this->successResponse('Berhasil! Dokumen berhasil diunggah!', $urlc400);
                                $statusParam = [
                                    'status_kelompok' => 'Menunggu Persetujuan C400',
                                    'file_status_c400' => 'Menunggu Persetujuan C400',
                                    'file_status_c400_dosbing1' => 'Menunggu Persetujuan C400',
                                    'file_status_c400_dosbing2' => 'Menunggu Persetujuan C400',
                                    'status_dosen_pembimbing_1' => 'Menunggu Persetujuan C400',
                                    'status_dosen_pembimbing_2' => 'Menunggu Persetujuan C400',
                                ];
                                ApiDokumenModel::uploadFileKel($id_kelompok, $statusParam);

                            } else {
                                return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                            }
                        } else {
                            return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                        }
                    } else {
                        return $response = $this->failureResponse('Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C300!');
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
    public function uploadC500Process(Request $request)
    {
        try {
            // Check if the token is still valid
            JWTAuth::checkOrFail();

            // Get the user from the JWT token in the request headers
            $jwtUser = JWTAuth::parseToken()->authenticate();

            $user = ApiDokumenModel::getById($jwtUser->user_id);

            $kelompok = ApiDokumenModel::pengecekan_kelompok_mahasiswa($user->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {

                // Validate the request
                $validator = Validator::make($request->all(), [
                    'c500' => 'required|file|mimes:pdf|max:50240',

                ]);

                // Check if validation fails
                if ($validator->fails()) {
                    return $response = $this->failureResponse('Gagal! Validasi dokumen tidak berhasil!');

                }

                // Upload path
                $upload_path = '/../../file/kelompok/c500';
                // Check and delete the existing file
                $id_kelompok = $kelompok->id_kelompok;

                // Check and delete the existing file
                $existingFile = ApiDokumenModel::getKelompokFile($id_kelompok);

                if ($existingFile->is_lulus_expo == 1) {
                    return $response = $this->failureResponse('Kelompok Anda sudah lulus Expo Project!');
                }

                if ($existingFile->file_status_c400 != "C400 Telah Disetujui") {
                    return $response = $this->failureResponse('Gagal mengunggah! C400 belum disetujui kedua dosen pembimbing!');
                }

                if ($existingFile->file_status_c500 == "C500 Telah Disetujui") {
                    return $response = $this->failureResponse('Gagal mengunggah! C500 telah disetujui kedua dosen pembimbing!');
                }

                if ($request->hasFile('c500') && $existingFile != null) {
                    if ($existingFile->file_name_c400 != null) {

                        $file = $request->file('c500');
                        // Generate a unique file name
                        $newFileName = 'c500-' . Str::slug($existingFile->nomor_kelompok, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                        // Check if the folder exists, if not, create it
                        if (!is_dir(public_path($upload_path))) {
                            mkdir(public_path($upload_path), 0755, true);
                        }

                        if ($existingFile->file_name_c500) {
                            $filePath = public_path($existingFile->file_path_c500 . '/' . $existingFile->file_name_c500);
                            if (file_exists($filePath) && !unlink($filePath)) {
                                return $response = $this->failureResponse('Gagal menghapus dokumen lama!');
                            }
                        }

                        // Move the uploaded file to the specified path
                        if ($file->move(public_path($upload_path), $newFileName)) {
                            // Save the new file details in the database
                            $urlc500 = url($upload_path . '/' . $newFileName);

                            $params = [
                                'file_name_c500' => $newFileName,
                                'file_path_c500' => $upload_path,
                            ];

                            $uploadFile = ApiDokumenModel::uploadFileKel($id_kelompok, $params);

                            if ($uploadFile) {
                                return $response = $this->successResponse('Berhasil! Dokumen berhasil diunggah!', $urlc500);
                                $statusParam = [
                                    'status_kelompok' => 'Menunggu Persetujuan C500',
                                    'file_status_c500' => 'Menunggu Persetujuan C500',
                                    'file_status_c500_dosbing1' => 'Menunggu Persetujuan C500',
                                    'file_status_c500_dosbing2' => 'Menunggu Persetujuan C500',
                                    'status_dosen_pembimbing_1' => 'Menunggu Persetujuan C500',
                                    'status_dosen_pembimbing_2' => 'Menunggu Persetujuan C500',
                                ];

                                ApiDokumenModel::uploadFileKel($id_kelompok, $statusParam);
                            } else {
                                return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                            }
                        } else {
                            return $response = $this->failureResponse('Gagal! Dokumen gagal diunggah!');
                        }
                    } else {
                        return $response = $this->failureResponse('Gagal mengunggah! Lengkapi terlebih dahulu Dokumen C400!');
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
