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

            foreach ($rs_broadcast as $key =>$broadcast ) {

                $storagePath = null;

                // dd( $broadcast);
                if ($broadcast->broadcast_image_name != "" && $broadcast->broadcast_image_name != null) {
                    $storagePath = public_path($broadcast->broadcast_image_path . $broadcast->broadcast_image_name);

                    if (file_exists($storagePath)) {
                        $base64Image = base64_encode(file_get_contents($storagePath));
                        $rs_broadcast[$key]->broadcast_image_path = $base64Image;
                    } else {
                        $base64Image = base64_encode(file_get_contents(public_path('img/broadcast/default_broadcast.png')));
                        $rs_broadcast[$key]->broadcast_image_path = $base64Image;
                    }
                } else {
                    $base64Image = base64_encode(file_get_contents(public_path('img/broadcast/default_broadcast.png')));
                    $rs_broadcast[$key]->broadcast_image_name = $base64Image;
                }

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

            foreach ($rs_broadcast as $key =>$broadcast ) {

                $storagePath = null;

                // dd( $broadcast);
                if ($broadcast->broadcast_image_name != "" && $broadcast->broadcast_image_name != null) {
                    $storagePath = public_path($broadcast->broadcast_image_path . $broadcast->broadcast_image_name);

                    if (file_exists($storagePath)) {
                        $base64Image = base64_encode(file_get_contents($storagePath));
                        $rs_broadcast[$key]->broadcast_image_path = $base64Image;
                    } else {
                        $base64Image = base64_encode(file_get_contents(public_path('img/broadcast/default_broadcast.png')));
                        $rs_broadcast[$key]->broadcast_image_path = $base64Image;
                    }
                } else {
                    $base64Image = base64_encode(file_get_contents(public_path('img/broadcast/default_broadcast.png')));
                    $rs_broadcast[$key]->broadcast_image_name = $base64Image;
                }

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




   // New method for API endpoint
   public function detailBroadcastApi(Request $request)
   {

    $id = $request-> id_broadcast;

       $broadcast = ApiBroadcastModel::getDataById($id);

       if (empty($broadcast)) {
        $response = [
            'status' => false,
            'message' => 'Error mendapatkan data.',
            'data' => null,
        ];
        return response()->json($response); // 500 Internal Server Error
    }

    $response = [
        'status' => true,
        'message' => 'Berhasil mendapatkan data.',
        'data' => ['broadcast' => $broadcast],
    ];

    return response()->json($response);   }

}
