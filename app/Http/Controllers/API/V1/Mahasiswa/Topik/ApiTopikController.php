<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Topik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Topik\ApiTopikModel;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiTopikController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user && $user->user_active == 1) {
                $rs_topik = ApiTopikModel::getTopik();

                $response = $this->successResponse('Berhasil mendapatkan data topik!', ['rs_topik' => $rs_topik]);
            } else {
                $response = $this->failureResponse('Gagal mendapatkan data topik!', null);
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
