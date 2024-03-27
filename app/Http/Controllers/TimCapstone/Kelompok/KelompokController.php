<?php

namespace App\Http\Controllers\TimCapstone\Kelompok;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Kelompok\KelompokModel;
use App\Models\Mahasiswa\Kelompok_Mahasiswa\MahasiswaKelompokModel;
use Illuminate\Support\Facades\Hash;


class KelompokController extends BaseController
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
        $rs_kelompok = KelompokModel::getDataWithPagination();
        // dd($rs_kelompok);
        // data
        $data = ['rs_kelompok' => $rs_kelompok];
        // view
        return view('tim_capstone.kelompok-valid.index', $data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addMahasiswaKelompok(Request $request)
    {

        // params
        $params = [
            'id_kelompok' => $request->id_kelompok,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];
        // dd($params);
        // process
        if (KelompokModel::updateKelompokMHS($request->id_mahasiswa_nokel, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return back();
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back();
        }
    }

    public function addDosenKelompok(Request $request)
    {
        $cekDosen = KelompokModel::checkStatusDosen( $request->id_kelompok, $request->id_dosen);
        $cekPosisi = KelompokModel::checkPosisi( $request->id_kelompok, $request->status_dosen);

        // dd($cekPosisi);
        if ($cekDosen || $cekPosisi ) {
            session()->flash('danger', 'Dosen / Posisi Sudah ada');
            return back();
        }
        // params
        $params = [
            'id_kelompok' => $request->id_kelompok,
            'id_dosen' => $request->id_dosen,
            'status_dosen' => $request->status_dosen,
            'status_persetujuan' => 'menunggu persetujuan',
        ];
        // dd($params);
        // process
        if (KelompokModel::insertDosenKelompok($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return back();
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailKelompok($id)
    {

        // get data with pagination
        $kelompok = KelompokModel::getDataById($id);
        $rs_topik = KelompokModel::getTopik();
        $rs_mahasiswa = KelompokModel::listKelompokMahasiswa($id);
        $rs_mahasiswa_nokel = KelompokModel::listKelompokMahasiswaNokel($kelompok->id_topik);
        $rs_dosbing = MahasiswaKelompokModel::getAkunDosbingKelompok($id);
        $rs_dospenguji = KelompokModel::listDospenguji($id);
        $rs_dosbing_avail = KelompokModel::listDosbingAvail();
        $rs_dosbing_avail_arr = [];

        // dd($rs_dosbing_avail);

        foreach ($rs_dosbing_avail as $key => $dosbing) {
            $check_dosen_kelompok = KelompokModel::checkDosbing($id, $dosbing->user_id);
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
            return redirect('/admin/kelompok');
        }

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
        return view('tim_capstone.kelompok-valid.detail', $data);
    }

    public function deleteKelompokProcess($id)
    {

        // get data
        $kelompok = KelompokModel::getDataById($id);

        // if exist
        if (!empty($kelompok)) {
            $cekMhs=KelompokModel::getKelompokMhsAll($kelompok->id);
            foreach ($cekMhs as $key => $mhs) {
                KelompokModel::deleteKelompokMhs($mhs->id_mahasiswa);
            }

            if (KelompokModel::deleteJadwalSidangProposal($kelompok->id)) {
                if (KelompokModel::deleteKelompok($kelompok->id)) {
                    // flash message
                    session()->flash('success', 'Data berhasil dihapus.');
                    return back();
                } else {
                    // flash message
                    session()->flash('danger', 'Data gagal dihapus.');
                    return back();
                }
            } else {
                if (KelompokModel::deleteKelompok($kelompok->id)) {
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteKelompokMahasiswaProcess($id_mahasiswa, $id)
    {

        // get data
        $mahasiswa = KelompokModel::getKelompokMhs($id_mahasiswa, $id);

        // if exist
        if (!empty($mahasiswa)) {
            // process
            if (KelompokModel::deleteKelompokMhs($mahasiswa->id)) {
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

    public function deleteKelompokDosenProcess($id_dosen, $id)
    {

        $kelompok = KelompokModel::getKelompokById($id);

        $params ="";

        if ($id_dosen == $kelompok -> id_dosen_pembimbing_1) {
            $params = [
                'id_dosen_pembimbing_1' => null,
                'status_dosen_pembimbing_1' => null,
            ];
        } else if ($id_dosen == $kelompok -> id_dosen_pembimbing_2) {
            $params = [
                'id_dosen_pembimbing_2' => null,
                'status_dosen_pembimbing_2' => null,
            ];
        } else {
            $params = null;
        }

        // dd($params);
        // get data
        $dosen = KelompokModel::updateKelompok($id_dosen, $id, $params);

        // if exist
        if (!empty($dosen)) {
            // process
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return back();
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editKelompokProcess(Request $request)
    {

        // Validate & auto redirect when fail
        $rules = [
            'id' => 'required',
            "nomor_kelompok" => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            "nomor_kelompok" => $request->nomor_kelompok,
            "judul_capstone" => $request->judul_ta,
            "id_topik" => $request->topik,
            "status_kelompok" => $request->status_kelompok,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (KelompokModel::updateKelompokNomor($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return back();
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back();
        }
    }

    public function setujuiKelompok(Request $request)
    {

        // Validate & auto redirect when fail
        $rules = [
            'id' => 'required',
            "status_kelompok" => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            "status_kelompok" => $request->status_kelompok,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (KelompokModel::updateKelompokNomor($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return back();
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back();
        }
    }


    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_kelompok = KelompokModel::getDataSearch($nama);
            // data
            $data = ['rs_kelompok' => $rs_kelompok, 'nama' => $nama];
            // view
            return view('tim_capstone.kelompok-valid.index', $data);
        } else {
            return redirect('/admin/kelompok');
        }
    }
}
