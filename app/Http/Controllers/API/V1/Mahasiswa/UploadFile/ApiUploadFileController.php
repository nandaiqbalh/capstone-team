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


use Barryvdh\DomPDF\Facade as PDF;

class ApiUploadFileController extends Controller
{

    public function index(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            $response = [
                'status' => false,
                'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                'data' => null,
            ];
            return response()->json($response, ); // 400 Bad Request
        }

        $userId = $request->input('user_id');
        $user = ApiAccountModel::getById($userId);

        // Check if the user exists
        if ($user != null) {
            // Attempt to authenticate the user based on api_token
            if ($user->api_token != null) {
                // Authorize
                $isAuthorized = ApiAccountModel::authorize('R', $userId);

                if (!$isAuthorized) {
                    $response = [
                        'status' => false,
                        'message' => 'Akses tidak sah!',
                        'data' => null,
                    ];
                    return response()->json($response, );
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Data

                        try {
                            // get data kelompok
                            $file_mhs = ApiUploadFileModel::fileMHS($user ->user_id);

                            // data
                            $data = ['file_mhs' => $file_mhs];

                            // response
                            return response()->json(['status' => true, 'message' => "Berhasil mendapatkan data.", 'data' => $data]);
                        } catch (\Exception $e) {
                            // handle unexpected errors
                            return response()->json(['status' => false, 'message' => $e->getMessage()]);
                        }
                    } else {
                        $response = [
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                            'data' => null,
                        ];
                        return response()->json($response, ); // 401 Unauthorized
                    }
                }
            }
        } else {
            // User not found or api_token is null
            $response = [
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'data' => null,
            ];
            return response()->json($response, ); // 401 Unauthorized
        }
    }

    public function uploadMakalahProcess(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
            ], ); // 400 Bad Request
        }

        $userId = $request->input('user_id');
        $user = ApiAccountModel::getById($userId);

        // Check if the user exists
        if ($user != null) {
            // Attempt to authenticate the user based on api_token
            if ($user->api_token != null) {
                // Authorize
                $isAuthorized = ApiAccountModel::authorize('C', $userId);

                if (!$isAuthorized) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Akses tidak sah!',
                    ], );
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Validate the request
                        $validator = Validator::make($request->all(), [
                            'makalah' => 'required|file|mimes:pdf|max:10240',
                            'id_mahasiswa' => 'required|exists:kelompok_mhs,id_mahasiswa',
                        ]);

                        // Check if validation fails
                        if ($validator->fails()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Validation error',
                                'errors' => $validator->errors(),
                            ], );
                        }

                        // Upload path
                        $uploadPath = '/file/mahasiswa/makalah';

                        // Upload FOTO
                        if ($request->hasFile('makalah')) {
                            $file = $request->file('makalah');

                            $file_extention = pathinfo(
                                $file->getClientOriginalName(),
                                PATHINFO_EXTENSION
                            );

                            // Generate a unique file name
                            $newFileName  = 'makalah' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

                            // Check if the folder exists, if not, create it
                            if (!is_dir(public_path($uploadPath))) {
                                mkdir(public_path($uploadPath), 0755, true);
                            }

                            // Check and delete the existing file
                            $existingFile = ApiUploadFileModel::fileMHS($request->id_mahasiswa);

                            // Check if the file exists
                            if ($existingFile -> file_name_makalah != null) {
                                // Construct the file path
                                $filePath = public_path($existingFile->file_path_makalah . '/' . $existingFile->file_name_makalah);

                                // Check if the file exists before attempting to delete
                                if (file_exists($filePath)) {
                                    // Attempt to delete the file
                                    if (!unlink($filePath)) {
                                        // Return failure response if failed to delete the existing file
                                        return response()->json([
                                            'status' => false,
                                            'message' => 'Gagal menghapus file lama.',
                                        ], );
                                    }
                                }
                            }

                            // Move the uploaded file to the specified path
                            if ($file->move(public_path($uploadPath), $newFileName)) {
                                // Save the new file details in the database
                                $params = [
                                    'file_name_makalah' => $newFileName,
                                    'file_path_makalah' => $uploadPath,
                                ];

                                $uploadFile = ApiUploadFileModel::uploadFileMHS($request->id_mahasiswa, $params);

                                if ($uploadFile) {
                                    // Return success response
                                    return response()->json([
                                        'status' => true,
                                        'message' => 'Data berhasil disimpan.',
                                    ], );
                                } else {
                                    // Return failure response if failed to save new file details
                                    return response()->json([
                                        'status' => false,
                                        'message' => 'Gagal menyimpan file.',
                                    ], );
                                }
                            } else {
                                // Return failure response if failed to move the uploaded file
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Makalah gagal diupload.',
                                ], );
                            }
                        }

                        return response()->json([
                            'status' => false,
                            'message' => 'Makalah tidak ditemukan.',
                        ], );
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                        ], ); // 401 Unauthorized
                    }
                }
            }
        } else {
            // User not found or api_token is null
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
            ], ); // 401 Unauthorized
        }
    }

    public function uploadLaporanProcess(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
            ], );
        }

        $userId = $request->input('user_id');
        $user = ApiAccountModel::getById($userId);

        // Check if the user exists
        if ($user != null) {
            // Attempt to authenticate the user based on api_token
            if ($user->api_token != null) {
                // Authorize
                $isAuthorized = ApiAccountModel::authorize('C', $userId);

                if (!$isAuthorized) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Akses tidak sah!',
                    ], );
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Validate the request
                        $validator = Validator::make($request->all(), [
                            'laporan_ta' => 'required|file|mimes:pdf|max:10240',
                            'id_mahasiswa' => 'required|exists:kelompok_mhs,id_mahasiswa',
                        ]);

                        // Check if validation fails
                        if ($validator->fails()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Validation error',
                                'errors' => $validator->errors(),
                            ], );
                        }

                        // Upload path
                        $uploadPath = '/file/mahasiswa/laporan-ta';

                        // Upload Laporan TA
                        if ($request->hasFile('laporan_ta')) {
                            $file = $request->file('laporan_ta');

                            // Generate a unique file name
                            $newFileName = 'laporan_ta_' . Str::slug($user->user_name, '-') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                            // Check if the folder exists, if not, create it
                            if (!is_dir(public_path($uploadPath))) {
                                mkdir(public_path($uploadPath), 0755, true);
                            }

                            // Check and delete the existing file
                            $existingFile = ApiUploadFileModel::fileMHS($request->id_mahasiswa);

                            // Check if the file exists
                            if ($existingFile -> file_name_laporan_ta != null) {
                                // Construct the file path
                                $filePath = public_path($existingFile->file_path_laporan_ta . '/' . $existingFile->file_name_laporan_ta);

                                // Check if the file exists before attempting to delete
                                if (file_exists($filePath)) {
                                    // Attempt to delete the file
                                    if (!unlink($filePath)) {
                                        // Return failure response if failed to delete the existing file
                                        return response()->json([
                                            'status' => false,
                                            'message' => 'Gagal menghapus file lama.',
                                        ], );
                                    }
                                }
                            }

                            // Move the uploaded file to the specified path
                            try {
                                $file->move(public_path($uploadPath), $newFileName);
                            } catch (\Exception $e) {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Laporan gagal diupload.',
                                ], );
                            }

                            // Save the file details in the database
                            $params = [
                                'file_name_laporan_ta' => $newFileName,
                                'file_path_laporan_ta' => $uploadPath,
                            ];

                            $uploadFile = ApiUploadFileModel::uploadFileMHS($request->id_mahasiswa, $params);

                            if ($uploadFile) {
                                return response()->json([
                                    'status' => true,
                                    'message' => 'Data berhasil disimpan.',
                                ], );
                            } else {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Gagal menyimpan file.',
                                ],);
                            }
                        }

                        return response()->json([
                            'status' => false,
                            'message' => 'Laporan tidak ditemukan.',
                        ], );
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                        ], );
                    }
                }
            }
        } else {
            // User not found or api_token is null
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
            ], );
        }
    }

    public function viewPdf(Request $request)
    {
         // Get api_token from the request body
         $apiToken = $request->input('api_token');

         // Check if api_token is provided
         if (empty($apiToken)) {
             return response()->json([
                 'status' => false,
                 'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
             ], );
         }

         $userId = $request->input('user_id');
         $user = ApiAccountModel::getById($userId);

         // Check if the user exists
         if ($user != null) {
             // Attempt to authenticate the user based on api_token
             if ($user->api_token != null) {
                 // Authorize
                 $isAuthorized = ApiAccountModel::authorize('C', $userId);

                 if (!$isAuthorized) {
                     return response()->json([
                         'status' => false,
                         'message' => 'Akses tidak sah!',
                     ], );
                 } else {
                     // Check if the provided api_token matches the user's api_token
                     if ($user->api_token == $apiToken) {

                        $filename = $request->input('filename');
                        $filepath = $request->input('filepath');

                        $storagePath = $filepath . $filename;

                        // Check if the file exists in storage
                        if (file_exists($storagePath)) {
                            // Read the file contents into a string
                            $fileContents = File::get($storagePath);

                            // Convert the string to base64
                            $base64File = base64_encode($fileContents);

                            return response()->json([
                                'status' => true,
                                'message' => 'Berhasil mendapatkan file PDF!',
                                'data' => $base64File,
                            ], );
                        } else {
                            // If file not found, return a 404 response
                            return response()->json(['status' => false, 'message' => 'File tidak ditemukan'], );
                        }
                     } else {
                         return response()->json([
                             'status' => false,
                             'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                         ], );
                     }
                 }
             }
         } else {
             // User not found or api_token is null
             return response()->json([
                 'status' => false,
                 'message' => 'Pengguna tidak ditemukan!',
             ], );
         }
    }

}
