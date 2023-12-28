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

            // Return JSON response with successful status
            $response = [
                'status' => true,
                'message' => 'Berhasil mendapatkan data.',
                'data' => ['rs_broadcast' => $rs_broadcast],
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            $response = [
                'status' => false,
                'message' => 'Error mendapatkan data.',
                'data' => null,
            ];
            return response()->json($response, 500); // 500 Internal Server Error
        }
    }

   // New method for API endpoint
   public function detailBroadcastApi($id)
   {

       $broadcast = ApiBroadcastModel::getDataById($id);

       if (empty($broadcast)) {
        $response = [
            'status' => false,
            'message' => 'Error mendapatkan data.',
            'data' => null,
        ];
        return response()->json($response, 500); // 500 Internal Server Error
    }

    $response = [
        'status' => true,
        'message' => 'Berhasil mendapatkan data.',
        'data' => ['broadcast' => $broadcast],
    ];

    return response()->json($response, 200);   }

}
