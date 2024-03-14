<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Api\Mahasiswa\Mahasiswa\ApiMahasiswaModel;

class ApiMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $this->getAuthenticatedUser();

            if ($user != null && $user->user_active == 1) {
                $rs_mahasiswa = ApiMahasiswaModel::getDataMahasiswaAvailable();

                $data = [
                    'rs_mahasiswa' => $rs_mahasiswa,
                ];

                $response = $this->successResponse('Berhasil mendapatkan data mahasiswa!', $data);
            } else {
                $response = $this->failureResponse('Gagal mendapatkan data mahasiswa!');
            }
        } catch (JWTException $e) {
            $response = $this->failureResponse('Token is Invalid');
        }

        return response()->json($response);
    }

    private function getAuthenticatedUser()
    {
        return JWTAuth::parseToken()->authenticate();
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
