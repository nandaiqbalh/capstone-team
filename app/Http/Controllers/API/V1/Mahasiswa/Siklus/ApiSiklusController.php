<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Siklus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Mahasiswa\Siklus\ApiSiklusModel;
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

            $user = ApiSiklusModel::getAkunByID($jwtUser->user_id);

            // Check if the user exists
            if ($user != null && $user->user_active == 1) {
                // Data
                try {
                    $rs_siklus = ApiSiklusModel::getSiklusAktif();

                    if($rs_siklus->isNotEmpty()){

                        $periodePendaftaranCapstone = ApiSiklusModel::getPeriodePendaftaranSiklus();

                        if ($periodePendaftaranCapstone->isEmpty()) {
                            $response = $this->failureResponse('Belum memasuki periode pendaftaran capstone!');
                        } else {
                            $data = [
                                'rs_siklus' => $rs_siklus,
                                'periode_pendaftaran' => $periodePendaftaranCapstone
                            ];
                            $response = $this->successResponse('Berhasil mendapatkan data!', $data);
                        }
                    } else {
                        $response = $this->failureResponse('Tidak ada siklus tidak aktif!');
                    }

                } catch (\Exception $e) {
                    $response = $this->failureResponse('Internal Server Error');
                }
            } else {
                $response = $this->failureResponse('Pengguna tidak ditemukan!');
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response = $this->failureResponse('Pengguna tidak ditemukan!');
        }

        return response()->json($response);
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
