<?php

namespace App\Http\Controllers\API\V1\Mahasiswa\Broadcast;

use App\Http\Controllers\Controller;
use App\Models\Api\Mahasiswa\Broadcast\ApiBroadcastModel;
use Illuminate\Http\Request;

class ApiBroadcastController extends Controller
{

    public function index()
{
    try {
        // Get data with pagination
        $rs_broadcast = ApiBroadcastModel::getDataWithPagination();

        foreach ($rs_broadcast as $key => $broadcast) {
            $rs_broadcast[$key]->broadcast_image_url = $this->getBroadcastImageUrl($broadcast);
        }

        // Return JSON response with successful status
        $response = [
            'status' => true,
            'message' => 'Berhasil mendapatkan data.',
            'data' => ['rs_broadcast' => $rs_broadcast],
        ];

        return response()->json($response);
    } catch (\Exception $e) {
        // Handle any exceptions
        $response = [
            'status' => false,
            'message' => 'Error mendapatkan data.',
            'data' => null,
        ];

        return response()->json($response); // 500 Internal Server Error
    }
}




    public function broadcastHome()
    {
        try {
            // Get data with pagination
            $rs_broadcast = ApiBroadcastModel::getDataWithHomePagination();

            foreach ($rs_broadcast as $key => $broadcast) {
                $rs_broadcast[$key]->broadcast_image_url = $this->getBroadcastImageUrl($broadcast);
            }

            // Return JSON response with successful status
            $response = [
                'status' => true,
                'message' => 'Berhasil mendapatkan data.',
                'data' => ['rs_broadcast' => $rs_broadcast],
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response = [
                'status' => false,
                'message' => 'Error mendapatkan data.',
                'data' => null,
            ];

            return response()->json($response); // 500 Internal Server Error
        }
    }

    public function detailBroadcastApi(Request $request)
    {
        try {
            $id = $request->input('id');
            $broadcast = ApiBroadcastModel::getDataById($id);

            if (!empty($broadcast)) {
                $broadcast->broadcast_image_url = $this->getBroadcastImageUrl($broadcast);

                $response = [
                    'status' => true,
                    'message' => 'Berhasil mendapatkan data.',
                    'data' => ['broadcast' => $broadcast],
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Error mendapatkan data.',
                    'data' => null,
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response = [
                'status' => false,
                'message' => 'Error mendapatkan data.',
                'data' => null,
            ];

            return response()->json($response); // 500 Internal Server Error
        }
    }

    private function getBroadcastImageUrl($broadcast)
    {
        if (!empty($broadcast->broadcast_image_name)) {
            $imageUrl = url($broadcast->broadcast_image_path . $broadcast->broadcast_image_name);
        } else {
            $imageUrl = url('img/broadcast/default_broadcast.png');
        }

        return $imageUrl;
    }

}
