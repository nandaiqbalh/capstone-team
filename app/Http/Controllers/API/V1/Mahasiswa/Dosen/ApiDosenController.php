<?php

namespace App\Http\Controllers\Api\V1\Mahasiswa\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Dosen\ApiDosenModel;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiDosenController extends Controller
{
    public function dosbing1(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user && $user->user_active == 1) {
                $rs_dosen = ApiDosenModel::getDataDosbing1();

                $response = $this->successResponse('Berhasil mendapatkan data dosen!', ['rs_dosen' => $rs_dosen]);
            } else {
                $response = $this->failureResponse('Gagal mendapatkan data dosen!', null);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = $this->failureResponse('Token is Invalid', null);
        }

        return response()->json($response);
    }

    public function dosbing2(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user && $user->user_active == 1) {
                $rs_dosen = ApiDosenModel::getDataDosbing2();

                $response = $this->successResponse('Berhasil mendapatkan data dosen!', ['rs_dosen' => $rs_dosen]);
            } else {
                $response = $this->failureResponse('Gagal mendapatkan data dosen!', null);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = $this->failureResponse('Token is Invalid', null);
        }

        return response()->json($response);
    }

    // Fungsi untuk membuat respons sukses
    private function successResponse($statusMessage, $data)
    {
        return [
            'success' => true,
            'status' => $statusMessage,
            'data' => $data,
        ];
    }

    // Fungsi untuk membuat respons kegagalan
    private function failureResponse($statusMessage, $data)
    {
        return [
            'success' => false,
            'status' => $statusMessage,
            'data' => $data,
        ];
    }
}
