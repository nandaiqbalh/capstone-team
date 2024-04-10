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

        foreach ($rs_bimbingan as $bimbingan) {
            $bimbingan -> status_individu_color = $this->getStatusColor($bimbingan->status_individu);
        }

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

    public function detailMahasiswa($user_id)
    {

        // get data with pagination
        $mahasiswa = PembimbingMahasiswaModel::getDataMahasiswaBimbinganById($user_id);

        $mahasiswa -> status_individu_color = $this->getStatusColor($mahasiswa->status_individu);
        $mahasiswa -> status_kelompok_color = $this->getStatusColor($mahasiswa->status_kelompok);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }
        $rs_peminatan = PembimbingMahasiswaModel::peminatanMahasiswa($user_id);

        foreach ($rs_peminatan as $key => $peminatan) {
            if ($peminatan->id == $mahasiswa->id_peminatan_individu1) {
                $peminatan->prioritas = "Prioritas 1";
            } else if($peminatan->id == $mahasiswa->id_peminatan_individu2) {
                $peminatan->prioritas = "Prioritas 2";
            }else if($peminatan->id == $mahasiswa->id_peminatan_individu3) {
                $peminatan->prioritas = "Prioritas 3";
            }else if($peminatan->id == $mahasiswa->id_peminatan_individu4) {
                $peminatan->prioritas = "Prioritas 4";
            } else {
                $peminatan->prioritas = "Belum memilih";

            }
        }
        // dd($mahasiswa);
        // data
        $data = [
            'mahasiswa' => $mahasiswa,
            'rs_peminatan'=>$rs_peminatan
        ];

        // view
        return view('dosen.mahasiswa-bimbingan.detail-mahasiswa', $data);
    }

}
