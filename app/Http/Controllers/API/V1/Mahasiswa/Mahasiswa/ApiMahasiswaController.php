<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\Mahasiswa;

use App\Http\Controllers\TimCapstone\BaseController;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\Api\TimCapstone\Mahasiswa\ApiMahasiswaModel;

class ApiMahasiswaController extends BaseController
{

    public function index(Request $request)
    {
         // Get api_token and user_id from the request body
        $apiToken = $request->input('api_token');
        $userId = $request->input('user_id');

        // Check if api_token is provided
        if (empty($apiToken)) {
            $response = [
                'status' => false,
                'message' => 'Sesi anda telah berakhir, silahkan masuk terlebih dahulu.',
                'data' => null,
            ];
            return response()->json($response); // 400 Bad Request
        }

        $user = ApiAccountModel::getById($userId);

        // Check if the user exists
        if ($user != null) {
            // Attempt to authenticate the user based on api_token
            if ($user->api_token != null) {
                // Authorize
                $isAuthorized = ApiAccountModel::authorize('U', $userId);

                if (!$isAuthorized) {

                    $response = [
                        'status' => false,
                        'message' => 'Akses tidak sah!',
                        'data' => null,
                    ];
                    return response()->json($response);
                } else {
                    // Check if the provided api_token matches the user's api_token
                    if ($user->api_token == $apiToken) {
                        // Data
                        $mahasiswa = ApiMahasiswaModel::getData();

                        $response = [
                            'status' => true,
                            'message' => 'Berhasil mendapatkan data pengguna!',
                            'data' => $mahasiswa,
                        ];
                        // Return JSON response for the API
                        return response()->json($response);

                    } else {
                        $response = [
                            'status' => false,
                            'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                            'data' => null,
                        ];
                        return response()->json($response); // 401 Unauthorized
                    }
                }
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Pengguna harus login terlebih dahulu!',
                    'data' => null,
                ];
                return response()->json($response); // 401 Unauthorized
            }
        } else {
            // User not found or api_token is null
            $response = [
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'data' => null,
            ];
            return response()->json($response); // 401 Unauthorized
        }
    }

}
