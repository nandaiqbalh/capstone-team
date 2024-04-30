<?php

namespace App\Http\Controllers\TimCapstone\Kelompok\KelompokValid;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Kelompok\KelompokValid\KelompokValidModel;
use App\Models\Mahasiswa\Kelompok_Mahasiswa\MahasiswaKelompokModel;
use Illuminate\Support\Facades\Hash;


class KelompokValidController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {

        // get data with pagination
        $rs_kelompok = KelompokValidModel::getDataWithPagination();
        $rs_siklus = KelompokValidModel::getSiklusAktif();

        foreach ($rs_kelompok as $kelompok) {

            $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
            $kelompok -> status_dosbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
            $kelompok -> status_dosbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);

        }
        // data
        $data = [
            'rs_kelompok' => $rs_kelompok,
            'rs_siklus' => $rs_siklus,
        ];
        // view
        return view('tim_capstone.kelompok.kelompok-valid.index', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function filterSiklusKelompok(Request $request)
     {
         // data request
         $id_siklus = $request->id_siklus;
         $rs_siklus = KelompokValidModel::getSiklusAktif();

         // new search or reset
         if ($request->action == 'filter') {
             $rs_kelompok = KelompokValidModel::filterSiklusKelompok($id_siklus);
             $rs_siklus = KelompokValidModel::getSiklusAktif();
             $siklus = KelompokValidModel::getSiklusById($id_siklus);

             foreach ($rs_kelompok as $kelompok) {

                $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
                $kelompok -> status_dosbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
                $kelompok -> status_dosbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);

            }
             // data
             $data = [
                 'rs_kelompok' => $rs_kelompok,
                 'rs_siklus' => $rs_siklus,
                 'siklus' => $siklus,
                ];
             // view
             return view('tim_capstone.kelompok.kelompok-valid.index', $data);
         } else {
             return redirect('/tim-capstone/kelompok-valid');
         }
     }
    public function detailKelompok($id)
    {

        // get data with pagination
        $kelompok = KelompokValidModel::getDataById($id);
        $rs_topik = KelompokValidModel::getTopik();
        $rs_mahasiswa = KelompokValidModel::listKelompokMahasiswa($id);
        $rs_mahasiswa_nokel = KelompokValidModel::listKelompokMahasiswaNokel($kelompok->id_topik);
        $rs_dosbing = MahasiswaKelompokModel::getAkunDosbingKelompok($id);
        $rs_dospenguji = KelompokValidModel::listDospenguji($id);
        $rs_dosbing_avail = KelompokValidModel::listDosbingAvail();
        $rs_dosbing_avail_arr = [];

        // dd($rs_dosbing_avail);

        foreach ($rs_dosbing_avail as $key => $dosbing) {
            $check_dosen_kelompok = KelompokValidModel::checkDosbing($id, $dosbing->user_id);
            if ($check_dosen_kelompok) {
            }
            else {
                $rs_dosbing_avail_arr[] = $dosbing;
            }
        }

        foreach ($rs_dosbing as $dosbing) {

            if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_1) {
                $dosbing->jenis_dosen = 'Pembimbing 1';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_1;
            } else if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_2) {
                $dosbing->jenis_dosen = 'Pembimbing 2';
                $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_2;
            }

        }

        // check
        if (empty($kelompok)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/tim-capstone/kelompok-valid');
        }

        // status color
        $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
        $kelompok -> status_sidang_color = $this->getStatusColor($kelompok->status_sidang_proposal);
        $kelompok -> status_dosbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
        $kelompok -> status_dosbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);

        $kelompok -> status_c100_color = $this->getStatusColor($kelompok->file_status_c100);
        $kelompok -> status_c200_color = $this->getStatusColor($kelompok->file_status_c200);
        $kelompok -> status_c300_color = $this->getStatusColor($kelompok->file_status_c300);
        $kelompok -> status_c400_color = $this->getStatusColor($kelompok->file_status_c400);
        $kelompok -> status_c500_color = $this->getStatusColor($kelompok->file_status_c500);

        // data
        $data = [
            'kelompok' => $kelompok,
            'rs_topik' => $rs_topik,
            'rs_mahasiswa' => $rs_mahasiswa,
            'rs_dosbing' => $rs_dosbing,
            'rs_dospenguji' => $rs_dospenguji,
            'rs_mahasiswa_nokel' => $rs_mahasiswa_nokel,
            'rs_dosbing_avail' =>  $rs_dosbing_avail_arr
        ];
        // dd($data);

        // view
        return view('tim_capstone.kelompok.kelompok-valid.detail', $data);
    }

    public function deleteKelompokProcess($id)
    {

        // get data
        $kelompok = KelompokValidModel::getDataById($id);

        // if exist
        if (!empty($kelompok)) {
            $cekMhs=KelompokValidModel::getKelompokMhsAll($kelompok->id);
            foreach ($cekMhs as $key => $mhs) {
                KelompokValidModel::deleteKelompokMhs($mhs->id_mahasiswa);
            }

            if (KelompokValidModel::deleteJadwalSidangProposal($kelompok->id)) {
                if (KelompokValidModel::deleteKelompok($kelompok->id)) {
                    // flash message
                    session()->flash('success', 'Data berhasil dihapus.');
                    return back();
                } else {
                    // flash message
                    session()->flash('danger', 'Data gagal dihapus.');
                    return back();
                }
            } else {
                if (KelompokValidModel::deleteKelompok($kelompok->id)) {
                    // flash message
                    session()->flash('success', 'Data berhasil dihapus.');
                    return back();
                } else {
                    // flash message
                    session()->flash('danger', 'Data gagal dihapus.');
                    return back();
                }
            }
            // process

        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return back();
        }
    }

    public function deleteMahasiswaKelompokProcess($id_mahasiswa, $id)
    {

        // get data
        $mahasiswa = KelompokValidModel::getKelompokMhs($id_mahasiswa, $id);

         // params
         $params = [
            'id_kelompok' => NULL,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // if exist
        if (!empty($mahasiswa)) {
            // process
            if (KelompokValidModel::updateKelompokMHS($mahasiswa->id_mahasiswa, $params)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return back();
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return back();
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return back();
        }
    }


    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;
        $rs_siklus = KelompokValidModel::getSiklusAktif();

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_kelompok = KelompokValidModel::getDataSearch($nama);
            foreach ($rs_kelompok as $kelompok) {

                $kelompok -> status_kelompok_color = $this->getStatusColor($kelompok->status_kelompok);
                $kelompok -> status_dosbing1_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_1);
                $kelompok -> status_dosbing2_color = $this->getStatusColor($kelompok->status_dosen_pembimbing_2);

            }
            // data
            $data = ['rs_kelompok' => $rs_kelompok, 'rs_siklus' => $rs_siklus,  'nama' => $nama];
            // view
            return view('tim_capstone.kelompok.kelompok-valid.index', $data);
        } else {
            return redirect('/tim-capstone/kelompok-valid');
        }
    }
}
