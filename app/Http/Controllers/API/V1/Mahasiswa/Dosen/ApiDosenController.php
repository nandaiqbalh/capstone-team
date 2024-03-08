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
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Api\Mahasiswa\Dosen\ApiDosenModel;

class ApiDosenController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Get the user  from the JWT token in the request headers
            $user = JWTAuth::parseToken()->authenticate();

            // Additional checks or actions based on the retrieved user
            if ($user != null && $user -> user_active == 1) {
                $rs_dosen = ApiDosenModel::getData();

                $data = [
                    'rs_dosen' => $rs_dosen,
                ];

                $response = [
                    'message' => 'Gagal',
                    'success' => true,
                    'status' => 'Berhasil mendapatkan data dosen!',
                    'data' => $data,
                ];
            } else {
                $response = [
                    'message' => 'Gagal',
                    'success' => false,
                    'status' => 'Gagal mendapatkan data dosen!',
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

}
