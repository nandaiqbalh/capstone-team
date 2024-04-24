<?php

namespace App\Http\Controllers\TimCapstone\Balancing\PengujiTA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Balancing\PengujiTA\PengujiTAModel;
use Illuminate\Support\Facades\Hash;


class PengujiTAController extends BaseController
{
    public function balancingPengujiTA()
    {
        // get data with pagination
        $dt_dosen = PengujiTAModel::getDataBalancingPengujiTA();
        $rs_periode = PengujiTAModel::getPeriode();

        // data
        $data = [
            'dt_dosen' => $dt_dosen,
            'rs_periode' => $rs_periode,
        ];
        // view
        return view('tim_capstone.dosen.balancing.penguji-ta.index', $data);
    }



    public function detailBalancingPengujiTA($user_id)
    {
        // get data with pagination
        $rs_penguji_ta = PengujiTAModel::getDataPengujianTA($user_id);

        foreach ($rs_penguji_ta as $penguji_ta) {
            if ($penguji_ta->id_dosen_penguji_ta1 == $user_id) {
                $penguji_ta->jenis_dosen = 'Pembimbing 1';
                $penguji_ta -> status_dosen = $penguji_ta ->status_dosen_penguji_ta1;

            } else if ($penguji_ta->id_dosen_penguji_ta2 == $user_id) {
                $penguji_ta->jenis_dosen = 'Pembimbing 2';
                $penguji_ta -> status_dosen = $penguji_ta ->status_dosen_penguji_ta2;

            } else {
                $penguji_ta->jenis_dosen = 'Belum diplot';
            }
        }
        // data
        $data = ['rs_penguji_ta' => $rs_penguji_ta];
        // view
        return view('tim_capstone.dosen.balancing.penguji-ta.detail', $data);
    }


    public function detailMahasiswa($user_id)
    {

        // get data with pagination
        $mahasiswa = PengujiTAModel::getDataMahasiswaBimbinganById($user_id);

        $mahasiswa -> status_individu_color = $this->getStatusColor($mahasiswa->status_individu);
        $mahasiswa -> status_tugas_akhir_color = $this->getStatusColor($mahasiswa->status_tugas_akhir);
        $mahasiswa -> status_kelompok_color = $this->getStatusColor($mahasiswa->status_kelompok);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }
        $rs_peminatan = PengujiTAModel::peminatanMahasiswa($user_id);

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

    public function searchBalancingPengujiTA(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $dt_dosen = PengujiTAModel::searchBalancingPengujiTA($nama);
            $rs_periode = PengujiTAModel::getPeriode();
            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_periode' => $rs_periode,
                'nama' => $nama
            ];
            // view
            return view('tim_capstone.dosen.balancing.penguji-ta.index', $data);
        } else {
            return redirect('/admin/dosen');
        }
    }

    public function filterBalancingPengujiTA(Request $request)
    {
        // data request
        $id_periode = $request->id_periode;

        // new search or reset
        if ($request->action == 'search') {
            $dt_dosen = PengujiTAModel::getDataBalancingPengujiTAFilterPeriode($id_periode);
            $rs_periode = PengujiTAModel::getPeriode();

            // data
            $data = [
                'dt_dosen' => $dt_dosen,
                'rs_periode' => $rs_periode,
            ];
            // view
            return view('tim_capstone.dosen.balancing.penguji-ta.index', $data);
        } else {
            return redirect('/admin/balancing-penguji-ta');
        }
    }
}
