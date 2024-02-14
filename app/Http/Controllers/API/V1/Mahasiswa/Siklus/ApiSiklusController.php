<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Siklus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use App\Models\Api\Mahasiswa\Kelompok\ApiKelompokSayaModel;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class ApiSiklusController extends Controller
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
            // Data
            try {
                $rs_siklus = ApiKelompokSayaModel::getSiklusAktif();

                // belum memiliki kelompok
                $data = [
                    'rs_siklus' => $rs_siklus,
                ];

                $response = [
                    'message' => 'OK',
                    'status' => 'Berhasil mendapatkan data.',
                    'success' => true,
                    'data' => $data,
                ];
            } catch (\Exception $e) {
                $response = [
                    'message' => 'Internal Server Error',
                    'status' => $e->getMessage(),
                    'success' => false,
                    'data' => null,
                ];
            }
        } else {
            $response = [
                'message' => 'Unauthorized',
                'status' => 'Pengguna tidak ditemukan!',
                'success' => false,
                'data' => null,
            ];
        }
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        $response = [
            'message' => 'Unauthorized',
            'status' => $e->getMessage(),
            'success' => false,
            'data' => null,
        ];
    }

    return response()->json($response);
}

}
