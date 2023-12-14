<?php

namespace App\Http\Controllers\TimCapstone\JadwalPendaftaranKelompok;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\JadwalPendaftaranKelompok\JadwalPendaftaranKelompokModel;
use Illuminate\Support\Facades\Hash;


class JadwalPendaftaranKelompokController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {
        // authorize
        JadwalPendaftaranKelompokModel::authorize('R');

        // get data with pagination
        $rs_pendaftaran = JadwalPendaftaranKelompokModel::getDataWithPagination();
        $rs_siklus = JadwalPendaftaranKelompokModel::getSiklus();
        // data
        $data = [
            'rs_pendaftaran' => $rs_pendaftaran,
            'rs_siklus' => $rs_siklus
        ];
        // dd($data);
        // view
        return view('tim_capstone.jadwal-pendaftaran.kelompok.index', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addJadwalPendaftaranKelompokProcess(Request $request)
    {

        // authorize
        JadwalPendaftaranKelompokModel::authorize('C');
        // params
        // default passwordnya mahasiswa123

        $params = [
            'siklus_id' => $request->siklus_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];
        // dd($params);

        // process
        $insert = JadwalPendaftaranKelompokModel::insertJadwalPendaftaranKelompok($params);
        if ($insert) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/jadwal-pendaftaran/kelompok');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/jadwal-pendaftaran/kelompok/add')->withInput();
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editJadwalPendaftaranKelompokProcess(Request $request)
    {
        // authorize
        JadwalPendaftaranKelompokModel::authorize('U');

        // params
        $params = [
            'siklus_id' => $request->siklus_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (JadwalPendaftaranKelompokModel::updateJadwalPendaftaranKelompok($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/jadwal-pendaftaran/kelompok');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/jadwal-pendaftaran/kelompok/edit/' . $request->user_id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteJadwalPendaftaranKelompokProcess($id)
    {
        // authorize
        JadwalPendaftaranKelompokModel::authorize('D');

        // get data
        $delete = JadwalPendaftaranKelompokModel::getDataById($id);

        // if exist
        if (!empty($delete)) {
            // process
            if (JadwalPendaftaranKelompokModel::deleteJadwalPendaftaranKelompok($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/jadwal-pendaftaran/kelompok');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/jadwal-pendaftaran/kelompok');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/jadwal-pendaftaran/kelompok');
        }
    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchMahasiswa(Request $request)
    {
        // authorize
        JadwalPendaftaranKelompokModel::authorize('R');
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_pendaftaran = JadwalPendaftaranKelompokModel::getDataSearch($user_name);
            // dd($rs_pendaftaran);
            // data
            $data = ['rs_pendaftaran' => $rs_pendaftaran, 'nama' => $user_name];
            // view
            return view('tim_capstone.pendaftaran.index', $data);
        } else {
            return redirect('/admin/jadwal-pendaftaran/kelompok');
        }
    }
}
