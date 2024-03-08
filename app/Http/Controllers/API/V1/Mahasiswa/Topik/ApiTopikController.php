<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Topik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Profile\ApiAccountModel;
use App\Models\Api\Mahasiswa\Kelompok\ApiKelompokSayaModel;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class ApiTopikController extends Controller
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
                $rs_topik = ApiKelompokSayaModel::getTopik();
                // belum memiliki kelompok
                $data = [
                    'rs_topik' => $rs_topik,
                ];

                $response = [
                    'success' => true,
                    'message' => 'OK',
                    'status' => 'Berhasil mendapatkan data.',
                    'data' => $data,
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Unauthorized',
                    'status' => 'Pengguna tidak ditemukan!',
                    'data' => null,
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Internal Server Error',
                'status' => $e->getMessage(),
                'data' => null,
            ];
        }

        return response()->json($response);
    }

}
