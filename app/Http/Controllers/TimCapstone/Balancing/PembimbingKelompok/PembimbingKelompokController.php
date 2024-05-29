<?php

namespace App\Http\Controllers\TimCapstone\Balancing\PembimbingKelompok;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Balancing\PembimbingKelompok\PembimbingKelompokModel;
use Illuminate\Support\Facades\Hash;


class PembimbingKelompokController extends BaseController
{

    public function balancingDosbingKelompok()
    {
        // get data with pagination
        $dt_dosen = PembimbingKelompokModel::getDataBalancingDosbingKelompok();
        $rs_siklus = PembimbingKelompokModel::getSiklusAktif();

        // data
        $data = [
            'dt_dosen' => $dt_dosen,
            'rs_siklus' => $rs_siklus,
        ];
        // view
        return view('tim_capstone.dosen.balancing.dosbing-kelompok.index', $data);
    }

    public function filterBalancingDosbingKelompok(Request $request)
    {
        // data request
        $id_siklus = $request->id_siklus;

        // new search or reset
        if ($request->action == 'filter') {
            $dt_dosen = PembimbingKelompokModel::getDataBalancingDosbingKelompokFilterSiklus($id_siklus);
            $rs_siklus = PembimbingKelompokModel::getSiklusAktif();

            $siklus = PembimbingKelompokModel::getSiklusById($id_siklus);

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
                'siklus' => $siklus,
            ];

            // view
            return view('tim_capstone.dosen.balancing.dosbing-kelompok.index', $data);
        } else {
            return redirect('/tim-capstone/balancing-dosbing-kelompok');
        }
    }

    public function detailBalancingDosbingKelompok($user_id)
    {
        // get data with pagination
        $rs_bimbingan = PembimbingKelompokModel::getDataBimbinganDosbingKelompok($user_id);

        foreach ($rs_bimbingan as $bimbingan) {
            if ($bimbingan->id_dosen_pembimbing_1 == $user_id) {
                $bimbingan->jenis_dosen = 'Pembimbing 1';
                $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_1;

            } else if ($bimbingan->id_dosen_pembimbing_2 == $user_id) {
                $bimbingan->jenis_dosen = 'Pembimbing 2';
                $bimbingan -> status_dosen = $bimbingan ->status_dosen_pembimbing_2;

            } else {
                $bimbingan->jenis_dosen = 'Belum Diplot';
            }
        }
        // data
        $data = ['rs_bimbingan' => $rs_bimbingan];
        // view
        return view('tim_capstone.dosen.balancing.dosbing-kelompok.detail', $data);
    }

    public function searchBalancingDosbingKelompok(Request $request)
    {
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $dt_dosen = PembimbingKelompokModel::searchBalancingDosbingKelompok($user_name);
            $rs_siklus = PembimbingKelompokModel::getSiklusAktif();

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
                'nama' => $user_name
            ];
            // view
            return view('tim_capstone.dosen.balancing.dosbing-kelompok.index', $data);
        } else {
            return redirect('/tim-capstone/dosen');
        }
    }

}
