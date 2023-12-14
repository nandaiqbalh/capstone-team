<?php

namespace App\Http\Controllers\TimCapstone;

use App\Http\Controllers\TimCapstone\BaseController;
use Illuminate\Http\Request;
use App\Models\TimCapstone\DashboardModel as Dashmo;
use Illuminate\Support\Facades\Auth;

class DashboardController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // session()->put('keyaaaaaa', 'value');
        // dd(session('login'));
        // authorize
        Dashmo::authorize('R');
        Dashmo::authorize('R');

        // get data with pagination
        $rs_broadcast = Dashmo::getBroadcast();
        $rs_jad_kel = Dashmo::getJadwalCap();
        $rs_jad_sidang = Dashmo::getJadwalSidang();
        $rs_jad_expo = Dashmo::getJadwalExpo();
        // dd($rs_broadcast);


        // data

        $data = [
            'rs_broadcast' => $rs_broadcast,
            'rs_jad_kel' => $rs_jad_kel,
            'rs_jad_sidang' => $rs_jad_sidang,
            'rs_jad_expo' => $rs_jad_expo,
        ];

        //view
        return view('admin.dashboard.index', $data);
    }

}
