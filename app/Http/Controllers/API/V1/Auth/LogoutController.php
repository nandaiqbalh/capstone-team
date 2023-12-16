<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Api\V1\Auth\LogoutModel;

class LogoutController extends Controller
{
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // log logout
        $params = [
            'id'=> LogoutModel::makeMicrotimeID(),
            'user_id'   => Auth::user()->user_id,
            'status'=> 'logout',
            'ip_address'=> $request->ip(),
            'date'  => date('Y-m-d H:i:s'),
            'type'=> 'api'
        ];
        LogoutModel::insert_app_log($params);

        $user = $request->user();
        $user->currentAccessToken()->delete();

        // return json
        $response = [
            "status"=> true,
            "message"=> 'Berhasil'
        ];
        return response()->json($response)->setStatusCode(200);
       
    }
}
