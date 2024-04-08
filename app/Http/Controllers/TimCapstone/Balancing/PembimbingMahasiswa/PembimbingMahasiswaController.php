<?php

namespace App\Http\Controllers\TimCapstone\Balancing\PembimbingMahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Balancing\PembimbingMahasiswa\PembimbingMahasiswaModel;
use Illuminate\Support\Facades\Hash;


class PembimbingMahasiswaController extends BaseController
{
    public function balancingDosbingMahasiswa()
    {
        // get data with pagination
        $dt_dosen = PembimbingMahasiswaModel::getDataBalancingDosbingMahasiswa();
        $rs_siklus = PembimbingMahasiswaModel::getSiklusAktif();

        // data
        $data = [
            'dt_dosen' => $dt_dosen,
            'rs_siklus' => $rs_siklus,
        ];
        // view
        return view('tim_capstone.dosen.balancing.dosbing-mahasiswa.index', $data);
    }

    public function filterBalancingDosbingMahasiswa(Request $request)
    {
        // data request
        $id_siklus = $request->id_siklus;

        // new search or reset
        if ($request->action == 'search') {
            $dt_dosen = PembimbingMahasiswaModel::getDataBalancingDosbingMahasiswaFilterSiklus($id_siklus);
            $rs_siklus = PembimbingMahasiswaModel::getSiklusAktif();

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
            ];
            // view
            return view('tim_capstone.dosen.balancing.dosbing-mahasiswa.index', $data);
        } else {
            return redirect('/admin/balancing-dosbing-mahasiswa');
        }
    }

    public function detailBalancingDosbingMahasiswa($user_id)
    {
        // get data with pagination
        $rs_bimbingan = PembimbingMahasiswaModel::getDataBimbinganDosbingMahasiswa($user_id);

        // data
        $data = ['rs_bimbingan' => $rs_bimbingan];
        // view
        return view('tim_capstone.dosen.balancing.dosbing-mahasiswa.detail', $data);
    }

    public function searchBalancingDosbingMahasiswa(Request $request)
    {
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $dt_dosen = PembimbingMahasiswaModel::searchBalancingDosbingMahasiswa($user_name);
            $rs_siklus = PembimbingMahasiswaModel::getSiklusAktif();

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_siklus' => $rs_siklus,
                'nama' => $user_name
            ];
            // view
            return view('tim_capstone.dosen.balancing.dosbing-mahasiswa.index', $data);
        } else {
            return redirect('/admin/dosen');
        }
    }
}
