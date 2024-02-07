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


class ApiUploadFileCapstoneController extends Controller
{
    public function uploadC100Process(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            return response()->json([
                'status' => false,
                'message' => 'Sesi anda telah berakhir, silahkan masuk terlebih dahulu.',
                'newFileName' => null,

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
                        'newFileName' => null,

                    ], );
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Validate the request
                        $validator = Validator::make($request->all(), [
                            'c100' => 'required|file|mimes:pdf|max:10240',
                            'id' => 'required|exists:kelompok,id',
                        ]);

                        // Check if validation fails
                        if ($validator->fails()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Validation error',
                                'newFileName' => null,
                            ], );
                        }

                        // Upload path
                        $uploadPath = '/file/kelompok/c100';

                        // Upload Laporan TA
                        if ($request->hasFile('c100')) {
                            $file = $request->file('c100');

                            $file_extention = pathinfo(
                                $file->getClientOriginalName(),
                                PATHINFO_EXTENSION
                            );

                            // Generate a unique file name
                            $newFileName  = 'c100' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

                            // Check if the folder exists, if not, create it
                            if (!is_dir(public_path($uploadPath))) {
                                mkdir(public_path($uploadPath), 0755, true);
                            }

                            $id_kelompok = $request -> id;

                            // Check and delete the existing file
                            $existingFile = ApiUploadFileModel::getKelompokFile($id_kelompok);
                            // dd($existingFile);

                            // Check if the file exists
                            if ($existingFile -> file_name_c100 != null) {
                                // Construct the file path
                                $filePath = public_path($existingFile->file_path_c100 . '/' . $existingFile->file_name_c100);

                                // Check if the file exists before attempting to delete
                                if (file_exists($filePath)) {
                                    // Attempt to delete the file
                                    if (!unlink($filePath)) {
                                        // Return failure response if failed to delete the existing file
                                        return response()->json([
                                            'status' => false,
                                            'message' => 'Gagal menghapus file lama.',
                                            'newFileName' => null,

                                        ], );
                                    } else{

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
                                    'newFileName' => null,

                                ], );
                            }

                            // Save the file details in the database
                            $params = [
                                'file_name_c100' => $newFileName,
                                'file_path_c100' => $uploadPath,
                            ];

                            $uploadFile = ApiUploadFileModel::uploadFileKel($request->id, $params);

                            if ($uploadFile) {
                                return response()->json([
                                    'status' => true,
                                    'message' => 'Dokumen berhasil diunggah.',
                                    'newFileName' => $newFileName,

                                ], );
                            } else {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Gagal menyimpan file.',
                                    'newFileName' => null,

                                ], );
                            }
                        }

                        return response()->json([
                            'status' => false,
                            'message' => 'Laporan tidak ditemukan.',
                            'newFileName' => null,

                        ], );
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                            'newFileName' => null,

                        ], );
                    }
                }
            }
        } else {
            // User not found or api_token is null
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'newFileName' => null,

            ], );
        }
    }

    public function uploadC200Process(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            return response()->json([
                'status' => false,
                'message' => 'Sesi anda telah berakhir, silahkan masuk terlebih dahulu.',
                'newFileName' => null,

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
                        'newFileName' => null,

                    ], );
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Validate the request
                        $validator = Validator::make($request->all(), [
                            'c200' => 'required|file|mimes:pdf|max:10240',
                            'id' => 'required|exists:kelompok,id',
                        ]);

                        // Check if validation fails
                        if ($validator->fails()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Validation error',
                                'newFileName' => null,
                            ], );
                        }

                        // Upload path
                        $uploadPath = '/file/kelompok/c200';

                        // Upload Laporan TA
                        if ($request->hasFile('c200')) {
                            $file = $request->file('c200');

                            $file_extention = pathinfo(
                                $file->getClientOriginalName(),
                                PATHINFO_EXTENSION
                            );

                            // Generate a unique file name
                            $newFileName  = 'c200' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

                            // Check if the folder exists, if not, create it
                            if (!is_dir(public_path($uploadPath))) {
                                mkdir(public_path($uploadPath), 0755, true);
                            }

                            $id_kelompok = $request -> id;

                            // Check and delete the existing file
                            $existingFile = ApiUploadFileModel::getKelompokFile($id_kelompok);
                            // dd($existingFile);

                            // Check if the file exists
                            if ($existingFile -> file_name_c200 != null) {
                                // Construct the file path
                                $filePath = public_path($existingFile->file_path_c200 . '/' . $existingFile->file_name_c200);

                                // Check if the file exists before attempting to delete
                                if (file_exists($filePath)) {
                                    // Attempt to delete the file
                                    if (!unlink($filePath)) {
                                        // Return failure response if failed to delete the existing file
                                        return response()->json([
                                            'status' => false,
                                            'message' => 'Gagal menghapus file lama.',
                                            'newFileName' => null,

                                        ], );
                                    } else{

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
                                    'newFileName' => null,

                                ], );
                            }

                            // Save the file details in the database
                            $params = [
                                'file_name_c200' => $newFileName,
                                'file_path_c200' => $uploadPath,
                            ];

                            $uploadFile = ApiUploadFileModel::uploadFileKel($request->id, $params);

                            if ($uploadFile) {
                                return response()->json([
                                    'status' => true,
                                    'message' => 'Dokumen berhasil diunggah.',
                                    'newFileName' => $newFileName,

                                ], );
                            } else {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Gagal menyimpan file.',
                                    'newFileName' => null,

                                ], );
                            }
                        }

                        return response()->json([
                            'status' => false,
                            'message' => 'Laporan tidak ditemukan.',
                            'newFileName' => null,

                        ], );
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                            'newFileName' => null,

                        ], );
                    }
                }
            }
        } else {
            // User not found or api_token is null
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'newFileName' => null,

            ], );
        }
    }

    public function uploadC300Process(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            return response()->json([
                'status' => false,
                'message' => 'Sesi anda telah berakhir, silahkan masuk terlebih dahulu.',
                'newFileName' => null,

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
                        'newFileName' => null,

                    ], );
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Validate the request
                        $validator = Validator::make($request->all(), [
                            'c300' => 'required|file|mimes:pdf|max:10240',
                            'id' => 'required|exists:kelompok,id',
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
                        $uploadPath = '/file/kelompok/c300';

                        // Upload Laporan TA
                        if ($request->hasFile('c300')) {
                            $file = $request->file('c300');

                            $file_extention = pathinfo(
                                $file->getClientOriginalName(),
                                PATHINFO_EXTENSION
                            );

                            // Generate a unique file name
                            $newFileName  = 'c300' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

                            // Check if the folder exists, if not, create it
                            if (!is_dir(public_path($uploadPath))) {
                                mkdir(public_path($uploadPath), 0755, true);
                            }

                            $id_kelompok = $request -> id;

                            // Check and delete the existing file
                            $existingFile = ApiUploadFileModel::getKelompokFile($id_kelompok);
                            // Check if the file exists
                            if ($existingFile -> file_name_c300 != null) {
                                // Construct the file path
                                $filePath = public_path($existingFile->file_path_c300 . '/' . $existingFile->file_name_c300);

                                // Check if the file exists before attempting to delete
                                if (file_exists($filePath)) {
                                    // Attempt to delete the file
                                    if (!unlink($filePath)) {
                                        // Return failure response if failed to delete the existing file
                                        return response()->json([
                                            'status' => false,
                                            'message' => 'Gagal menghapus file lama.',
                                            'newFileName' => null,

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
                                    'newFileName' => null,

                                ], );
                            }

                            // Save the file details in the database
                            $params = [
                                'file_name_c300' => $newFileName,
                                'file_path_c300' => $uploadPath,
                            ];

                            $uploadFile = ApiUploadFileModel::uploadFileKel($request->id, $params);

                            if ($uploadFile) {
                                return response()->json([
                                    'status' => true,
                                    'message' => 'Dokumen berhasil diunggah.',
                                    'newFileName' => $newFileName,

                                ], );
                            } else {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Gagal menyimpan file.',
                                    'newFileName' => null,

                                ], );
                            }
                        }

                        return response()->json([
                            'status' => false,
                            'message' => 'Laporan tidak ditemukan.',
                            'newFileName' => null,

                        ], );
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                            'newFileName' => null,

                        ], );
                    }
                }
            }
        } else {
            // User not found or api_token is null
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'newFileName' => null,

            ], );
        }
    }

    public function uploadC400Process(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            return response()->json([
                'status' => false,
                'message' => 'Sesi anda telah berakhir, silahkan masuk terlebih dahulu.',
                'newFileName' => null,

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
                        'newFileName' => null,

                    ], );
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Validate the request
                        $validator = Validator::make($request->all(), [
                            'c400' => 'required|file|mimes:pdf|max:10240',
                            'id' => 'required|exists:kelompok,id',
                        ]);

                        // Check if validation fails
                        if ($validator->fails()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Validation error',
                                'newFileName' => null,

                            ], );
                        }

                        // Upload path
                        $uploadPath = '/file/kelompok/c400';

                        // Upload Laporan TA
                        if ($request->hasFile('c400')) {
                            $file = $request->file('c400');

                            $file_extention = pathinfo(
                                $file->getClientOriginalName(),
                                PATHINFO_EXTENSION
                            );

                            // Generate a unique file name
                            $newFileName  = 'c400' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

                            // Check if the folder exists, if not, create it
                            if (!is_dir(public_path($uploadPath))) {
                                mkdir(public_path($uploadPath), 0755, true);
                            }

                            $id_kelompok = $request -> id;

                            // Check and delete the existing file
                            $existingFile = ApiUploadFileModel::getKelompokFile($id_kelompok);

                            // Check if the file exists
                            if ($existingFile -> file_name_c400 != null) {
                                // Construct the file path
                                $filePath = public_path($existingFile->file_path_c400 . '/' . $existingFile->file_name_c400);

                                // Check if the file exists before attempting to delete
                                if (file_exists($filePath)) {
                                    // Attempt to delete the file
                                    if (!unlink($filePath)) {
                                        // Return failure response if failed to delete the existing file
                                        return response()->json([
                                            'status' => false,
                                            'message' => 'Gagal menghapus file lama.',
                                            'newFileName' => null,

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
                                    'newFileName' => null,

                                ], );
                            }

                            // Save the file details in the database
                            $params = [
                                'file_name_c400' => $newFileName,
                                'file_path_c400' => $uploadPath,
                            ];

                            $uploadFile = ApiUploadFileModel::uploadFileKel($request->id, $params);

                            if ($uploadFile) {
                                return response()->json([
                                    'status' => true,
                                    'message' => 'Dokumen berhasil diunggah.',
                                    'newFileName' => $newFileName,

                                ], );
                            } else {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Gagal menyimpan file.',
                                    'newFileName' => null,

                                ], );
                            }
                        }

                        return response()->json([
                            'status' => false,
                            'message' => 'Laporan tidak ditemukan.',
                            'newFileName' => null,

                        ], );
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                            'newFileName' => null,

                        ], );
                    }
                }
            }
        } else {
            // User not found or api_token is null
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'newFileName' => null,

            ], );
        }
    }

    public function uploadC500Process(Request $request)
    {
        // Get api_token from the request body
        $apiToken = $request->input('api_token');

        // Check if api_token is provided
        if (empty($apiToken)) {
            return response()->json([
                'status' => false,
                'message' => 'Sesi anda telah berakhir, silahkan masuk terlebih dahulu.',
                'newFileName' => null,

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
                        'newFileName' => null,

                    ], );
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Validate the request
                        $validator = Validator::make($request->all(), [
                            'c500' => 'required|file|mimes:pdf|max:10240',
                            'id' => 'required|exists:kelompok,id',
                        ]);

                        // Check if validation fails
                        if ($validator->fails()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Validation error',
                                'newFileName' => null,
                            ], );
                        }

                        // Upload path
                        $uploadPath = '/file/kelompok/c500';

                        // Upload Laporan TA
                        if ($request->hasFile('c500')) {
                            $file = $request->file('c500');

                            $file_extention = pathinfo(
                                $file->getClientOriginalName(),
                                PATHINFO_EXTENSION
                            );

                            // Generate a unique file name
                            $newFileName  = 'c500' . Str::slug($request->nama_mahasiswa, '-') . '-' . uniqid() . '.' . $file_extention;

                            // Check if the folder exists, if not, create it
                            if (!is_dir(public_path($uploadPath))) {
                                mkdir(public_path($uploadPath), 0755, true);
                            }

                            $id_kelompok = $request -> id;

                            // Check and delete the existing file
                            $existingFile = ApiUploadFileModel::getKelompokFile($id_kelompok);

                            // Check if the file exists
                            if ($existingFile -> file_name_c500 != null) {
                                // Construct the file path
                                $filePath = public_path($existingFile->file_path_c500 . '/' . $existingFile->file_name_c500);

                                // Check if the file exists before attempting to delete
                                if (file_exists($filePath)) {
                                    // Attempt to delete the file
                                    if (!unlink($filePath)) {
                                        // Return failure response if failed to delete the existing file
                                        return response()->json([
                                            'status' => false,
                                            'message' => 'Gagal menghapus file lama.',
                                            'newFileName' => null,

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
                                    'newFileName' => null,

                                ], );
                            }

                            // Save the file details in the database
                            $params = [
                                'file_name_c500' => $newFileName,
                                'file_path_c500' => $uploadPath,
                            ];

                            $uploadFile = ApiUploadFileModel::uploadFileKel($request->id, $params);

                            if ($uploadFile) {
                                return response()->json([
                                    'status' => true,
                                    'message' => 'Dokumen berhasil diunggah.',
                                    'newFileName' => $newFileName,
                                ], );
                            } else {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Gagal menyimpan file.',
                                    'newFileName' => null,

                                ], );
                            }
                        }

                        return response()->json([
                            'status' => false,
                            'message' => 'Laporan tidak ditemukan.',
                            'newFileName' => null,

                        ], );
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                            'newFileName' => null,

                        ], );
                    }
                }
            }
        } else {
            // User not found or api_token is null
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'newFileName' => null,

            ], );
        }
    }
}

