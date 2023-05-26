<?php

namespace App\Http\Controllers\Admin\JadwalExpo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\JadwalExpo\JadwalExpoModel;
use Illuminate\Support\Facades\Hash;


class JadwalExpoController extends BaseController
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
        JadwalExpoModel::authorize('R');

        // get data with pagination
        $rs_expo = JadwalExpoModel::getDataWithPagination();
        $rs_siklus = JadwalExpoModel::getSiklus();
        // data
        $data = [
            'rs_expo' => $rs_expo,
            'rs_siklus' => $rs_siklus
        ];
        // dd($data);
        // view
        return view('admin.jadwal-pendaftaran.expo.index', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addJadwalExpoProcess(Request $request)
    {

        // authorize
        JadwalExpoModel::authorize('C');
        // params
        // default passwordnya mahasiswa123

        $params = [
            'id_siklus' => $request->id_siklus,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];
        // dd($params);

        // process
        $insert = JadwalExpoModel::insertJadwalExpo($params);
        if ($insert) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/jadwal-pendaftaran/expo');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/jadwal-pendaftaran/expo')->withInput();
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editJadwalExpoProcess(Request $request)
    {
        // authorize
        JadwalExpoModel::authorize('U');

        // params
        $params = [
            'id_siklus' => $request->id_siklus,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (JadwalExpoModel::updateJadwalExpo($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/jadwal-pendaftaran/expo');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/jadwal-pendaftaran/expo/edit/' . $request->user_id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteJadwalExpoProcess($id)
    {
        // authorize
        JadwalExpoModel::authorize('D');

        // get data
        $delete = JadwalExpoModel::getDataById($id);

        // if exist
        if (!empty($delete)) {
            // process
            if (JadwalExpoModel::deleteJadwalExpo($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/jadwal-pendaftaran/expo');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/jadwal-pendaftaran/expo');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/jadwal-pendaftaran/expo');
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
        JadwalExpoModel::authorize('R');
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_expo = JadwalExpoModel::getDataSearch($user_name);
            // dd($rs_expo);
            // data
            $data = ['rs_expo' => $rs_expo, 'nama' => $user_name];
            // view
            return view('admin.pendaftaran.index', $data);
        } else {
            return redirect('/admin/jadwal-pendaftaran/expo');
        }
    }
}
