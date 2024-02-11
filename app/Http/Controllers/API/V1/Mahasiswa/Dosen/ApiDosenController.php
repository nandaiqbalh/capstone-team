<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\Api\Mahasiswa\Dosen\ApiDosenModel;

class ApiDosenController extends Controller
{

    public function index(Request $request)
    {
        // Get api_token and user_id from the Authorization header
        $apiToken = $request->header('Authorization');
        $userId = $request->input('user_id');

        // Check if api_token is provided
        if (empty($apiToken)) {
            $response = [
                'status' => false,
                'message' => 'Sesi anda telah berakhir, silahkan masuk terlebih dahulu.',
                'data' => null,
            ];
            return response()->json($response);
        }

        // Extract the token from the "Bearer" scheme if it's present
        $tokenParts = explode(' ', $apiToken);

        if (count($tokenParts) !== 2 || $tokenParts[0] !== 'Bearer') {
            $response = [
                'status' => false,
                'message' => 'Token tidak valid!',
                'data' => null,
            ];
            return response()->json($response);
        }

        $apiToken = $tokenParts[1];

        $user = ApiAccountModel::getById($userId);

        // Check if the user exists
        if ($user != null) {
            // Attempt to authenticate the user based on api_token
            if ($user->api_token != null) {
                // Check if the provided api_token matches the user's api_token
                if ($user->api_token == $apiToken) {
                    // Data
                    $rs_dosen = ApiDosenModel::getData();
                    $data = [
                        'rs_dosen' => $rs_dosen,
                    ];
                    $response = [
                        'status' => true,
                        'message' => 'Berhasil mendapatkan data pengguna!',
                        'data' => $data,
                    ];
                    // Return JSON response for the API
                    return response()->json($response);
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Gagal! Anda telah masuk melalui perangkat lain.',
                        'data' => null,
                    ];
                    return response()->json($response);
                }
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Pengguna harus login terlebih dahulu!',
                    'data' => null,
                ];
                return response()->json($response);
            }
        } else {
            // User not found or api_token is null
            $response = [
                'status' => false,
                'message' => 'Pengguna tidak ditemukan!',
                'data' => null,
            ];
            return response()->json($response);
        }
    }
}
