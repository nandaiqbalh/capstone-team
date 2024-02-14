<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\UploadFile;

use App\Http\Controllers\Controller;
use App\Models\Api\Mahasiswa\UploadFile\ApiUploadFileModel;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiUploadFileCapstoneController extends Controller
{
    public function uploadC100Process(Request $request)
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
                    'c100' => 'required|file|mimes:pdf|max:10240',
                    'id_kelompok' => 'required|exists:kelompok,id',

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
                $uploadPath = '/file/kelompok/c100';
                // Check and delete the existing file
                $id_kelompok = $request -> id_kelompok;

                // Check and delete the existing file
                $existingFile = ApiUploadFileModel::getKelompokFile($id_kelompok);
                                // Upload
                if ($request->hasFile('c100') && $existingFile != null) {
                    $file = $request->file('c100');



                    // Generate a unique file name
                    $newFileName = 'c100-' . Str::slug($existingFile->nomor_kelompok , '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Check if the folder exists, if not, create it
                    if (!is_dir(public_path($uploadPath))) {
                        mkdir(public_path($uploadPath), 0755, true);
                    }


                    if ($existingFile->file_name_c100) {
                        $filePath = public_path($existingFile->file_path_c100 . '/' . $existingFile->file_name_c100);

                        if (file_exists($filePath) && !unlink($filePath)) {
                            $response = [
                                'status' => 'Gagal menghapus dokumen lama.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];
                        }
                    } else {
                            $response = [
                                'status' => 'Gagal. Kelompok tidak valid.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];

                    }

                    // Move the uploaded file to the specified path
                    if ($file->move(public_path($uploadPath), $newFileName)) {
                        // Save the new file details in the database
                        $urlc100 = url($uploadPath . '/' . $newFileName);

                        $params = [
                            'file_name_c100' => $newFileName,
                            'file_path_c100' => $uploadPath,
                        ];

                        $uploadFile = ApiUploadFileModel::uploadFileKel($id_kelompok, $params);

                        if ($uploadFile) {
                            $response = [
                                'status' => 'Berhasil. Dokumen berhasil diunggah',
                                'message' => 'Berhasil',
                                'success' => true,
                                'data' => $urlc100,
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

    public function uploadC200Process(Request $request)
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
                    'c200' => 'required|file|mimes:pdf|max:10240',
                    'id_kelompok' => 'required|exists:kelompok,id',

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
                $uploadPath = '/file/kelompok/c200';
                // Check and delete the existing file
                $id_kelompok = $request -> id_kelompok;

                // Check and delete the existing file
                $existingFile = ApiUploadFileModel::getKelompokFile($id_kelompok);
                                // Upload
                if ($request->hasFile('c200') && $existingFile != null) {
                    $file = $request->file('c200');



                    // Generate a unique file name
                    $newFileName = 'c200-' . Str::slug($existingFile->nomor_kelompok , '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Check if the folder exists, if not, create it
                    if (!is_dir(public_path($uploadPath))) {
                        mkdir(public_path($uploadPath), 0755, true);
                    }


                    if ($existingFile->file_name_c200) {
                        $filePath = public_path($existingFile->file_path_c200 . '/' . $existingFile->file_name_c200);

                        if (file_exists($filePath) && !unlink($filePath)) {
                            $response = [
                                'status' => 'Gagal menghapus dokumen lama.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];
                        }
                    } else {
                            $response = [
                                'status' => 'Gagal. Kelompok tidak valid.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];

                    }

                    // Move the uploaded file to the specified path
                    if ($file->move(public_path($uploadPath), $newFileName)) {
                        // Save the new file details in the database
                        $urlc200 = url($uploadPath . '/' . $newFileName);

                        $params = [
                            'file_name_c200' => $newFileName,
                            'file_path_c200' => $uploadPath,
                        ];

                        $uploadFile = ApiUploadFileModel::uploadFileKel($id_kelompok, $params);

                        if ($uploadFile) {
                            $response = [
                                'status' => 'Berhasil. Dokumen berhasil diunggah',
                                'message' => 'Berhasil',
                                'success' => true,
                                'data' => $urlc200,
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

    public function uploadC300Process(Request $request)
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
                    'c300' => 'required|file|mimes:pdf|max:10240',
                    'id_kelompok' => 'required|exists:kelompok,id',

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
                $uploadPath = '/file/kelompok/c300';
                // Check and delete the existing file
                $id_kelompok = $request -> id_kelompok;

                // Check and delete the existing file
                $existingFile = ApiUploadFileModel::getKelompokFile($id_kelompok);
                                // Upload
                if ($request->hasFile('c300') && $existingFile != null) {
                    $file = $request->file('c300');



                    // Generate a unique file name
                    $newFileName = 'c300-' . Str::slug($existingFile->nomor_kelompok , '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Check if the folder exists, if not, create it
                    if (!is_dir(public_path($uploadPath))) {
                        mkdir(public_path($uploadPath), 0755, true);
                    }


                    if ($existingFile->file_name_c300) {
                        $filePath = public_path($existingFile->file_path_c300 . '/' . $existingFile->file_name_c300);

                        if (file_exists($filePath) && !unlink($filePath)) {
                            $response = [
                                'status' => 'Gagal menghapus dokumen lama.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];
                        }
                    } else {
                            $response = [
                                'status' => 'Gagal. Kelompok tidak valid.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];

                    }

                    // Move the uploaded file to the specified path
                    if ($file->move(public_path($uploadPath), $newFileName)) {
                        // Save the new file details in the database
                        $urlc300 = url($uploadPath . '/' . $newFileName);

                        $params = [
                            'file_name_c300' => $newFileName,
                            'file_path_c300' => $uploadPath,
                        ];

                        $uploadFile = ApiUploadFileModel::uploadFileKel($id_kelompok, $params);

                        if ($uploadFile) {
                            $response = [
                                'status' => 'Berhasil. Dokumen berhasil diunggah',
                                'message' => 'Berhasil',
                                'success' => true,
                                'data' => $urlc300,
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
    public function uploadC400Process(Request $request)
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
                    'c400' => 'required|file|mimes:pdf|max:10240',
                    'id_kelompok' => 'required|exists:kelompok,id',

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
                $uploadPath = '/file/kelompok/c400';
                // Check and delete the existing file
                $id_kelompok = $request -> id_kelompok;

                // Check and delete the existing file
                $existingFile = ApiUploadFileModel::getKelompokFile($id_kelompok);
                                // Upload
                if ($request->hasFile('c400') && $existingFile != null) {
                    $file = $request->file('c400');



                    // Generate a unique file name
                    $newFileName = 'c400-' . Str::slug($existingFile->nomor_kelompok , '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Check if the folder exists, if not, create it
                    if (!is_dir(public_path($uploadPath))) {
                        mkdir(public_path($uploadPath), 0755, true);
                    }


                    if ($existingFile->file_name_c400) {
                        $filePath = public_path($existingFile->file_path_c400 . '/' . $existingFile->file_name_c400);

                        if (file_exists($filePath) && !unlink($filePath)) {
                            $response = [
                                'status' => 'Gagal menghapus dokumen lama.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];
                        }
                    } else {
                            $response = [
                                'status' => 'Gagal. Kelompok tidak valid.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];

                    }

                    // Move the uploaded file to the specified path
                    if ($file->move(public_path($uploadPath), $newFileName)) {
                        // Save the new file details in the database
                        $urlc400 = url($uploadPath . '/' . $newFileName);

                        $params = [
                            'file_name_c400' => $newFileName,
                            'file_path_c400' => $uploadPath,
                        ];

                        $uploadFile = ApiUploadFileModel::uploadFileKel($id_kelompok, $params);

                        if ($uploadFile) {
                            $response = [
                                'status' => 'Berhasil. Dokumen berhasil diunggah',
                                'message' => 'Berhasil',
                                'success' => true,
                                'data' => $urlc400,
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
    public function uploadC500Process(Request $request)
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
                    'c500' => 'required|file|mimes:pdf|max:10240',
                    'id_kelompok' => 'required|exists:kelompok,id',

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
                $uploadPath = '/file/kelompok/c500';
                // Check and delete the existing file
                $id_kelompok = $request -> id_kelompok;

                // Check and delete the existing file
                $existingFile = ApiUploadFileModel::getKelompokFile($id_kelompok);
                                // Upload
                if ($request->hasFile('c500') && $existingFile != null) {
                    $file = $request->file('c500');



                    // Generate a unique file name
                    $newFileName = 'c500-' . Str::slug($existingFile->nomor_kelompok , '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Check if the folder exists, if not, create it
                    if (!is_dir(public_path($uploadPath))) {
                        mkdir(public_path($uploadPath), 0755, true);
                    }


                    if ($existingFile->file_name_c500) {
                        $filePath = public_path($existingFile->file_path_c500 . '/' . $existingFile->file_name_c500);

                        if (file_exists($filePath) && !unlink($filePath)) {
                            $response = [
                                'status' => 'Gagal menghapus dokumen lama.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];
                        }
                    } else {
                            $response = [
                                'status' => 'Gagal. Kelompok tidak valid.',
                                'message' => 'Gagal',
                                'success' => false,
                                'data' => null,
                            ];

                    }

                    // Move the uploaded file to the specified path
                    if ($file->move(public_path($uploadPath), $newFileName)) {
                        // Save the new file details in the database
                        $urlc500 = url($uploadPath . '/' . $newFileName);

                        $params = [
                            'file_name_c500' => $newFileName,
                            'file_path_c500' => $uploadPath,
                        ];

                        $uploadFile = ApiUploadFileModel::uploadFileKel($id_kelompok, $params);

                        if ($uploadFile) {
                            $response = [
                                'status' => 'Berhasil. Dokumen berhasil diunggah',
                                'message' => 'Berhasil',
                                'success' => true,
                                'data' => $urlc500,
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
}

