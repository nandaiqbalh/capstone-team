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
            $rs_broadcast = ApiBroadcastModel::getDataWithPagination();
            $this->addImageUrlToBroadcasts($rs_broadcast);

            $response = $this->successResponse('Berhasil mendapatkan data.', ['rs_broadcast' => $rs_broadcast]);

            return response()->json($response);
        } catch (\Exception $e) {
            $response = $this->failureResponse('Error mendapatkan data.');

            return response()->json($response);
        }
    }

    public function broadcastHome()
    {
        try {
            $rs_broadcast = ApiBroadcastModel::getDataWithHomePagination();
            $this->addImageUrlToBroadcasts($rs_broadcast);

            $response = $this->successResponse('Berhasil mendapatkan data.', ['rs_broadcast' => $rs_broadcast]);

            return response()->json($response);
        } catch (\Exception $e) {
            $response = $this->failureResponse('Error mendapatkan data.');

            return response()->json($response);
        }
    }

    public function detailBroadcastApi(Request $request)
    {
        try {
            $id = $request->input('id');
            $broadcast = ApiBroadcastModel::getDataById($id);

            if (!empty($broadcast)) {
                $broadcast->broadcast_image_url = $this->getBroadcastImageUrl($broadcast);

                $response = $this->successResponse('Berhasil mendapatkan data.', ['broadcast' => $broadcast]);
            } else {
                $response = $this->failureResponse('Error mendapatkan data.');
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response = $this->failureResponse('Error mendapatkan data.');

            return response()->json($response);
        }
    }

    private function addImageUrlToBroadcasts(&$broadcasts)
    {
        foreach ($broadcasts as $key => $broadcast) {
            $broadcasts[$key]->broadcast_image_url = $this->getBroadcastImageUrl($broadcast);
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
