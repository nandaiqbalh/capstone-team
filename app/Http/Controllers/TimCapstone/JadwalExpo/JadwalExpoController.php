<?php

namespace App\Http\Controllers\TimCapstone\JadwalExpo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\JadwalExpo\JadwalExpoModel;
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailJadwalExpo($id)
    {
        // authorize
        JadwalExpoModel::authorize('R');

        // get data with pagination
        $expo = JadwalExpoModel::getDataById($id);
        // dd($expo);
        // check
        if (empty($expo)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/jadwal-pendaftaran/expo');
        }

        $rs_kel_expo = JadwalExpoModel::getExpoDaftar($id);

        // data
        $data = [
            'expo' => $expo,
            'rs_kel_expo' => $rs_kel_expo
        ];

        // view
        return view('admin.jadwal-pendaftaran.expo.detail', $data);
    }
    /**
     * Update the specified resource in storage. update status dosen 1
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function terimaKelompok($id)
    {
        // authorize
        JadwalExpoModel::authorize('U');;

        // params
        $params = [
            'status' => 'disetujui',
        ];

        // process
        if (JadwalExpoModel::updateJadwalExpoKelompok($id, $params)) {
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
     * Update the specified resource in storage. update status dosen 2
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tolakKelompok($id)
    {
        // authorize
        JadwalExpoModel::authorize('U');;

        // params
        $params = [
            'status' => 'tidak disetujui',
        ];

        // process
        if (JadwalExpoModel::updateJadwalExpoKelompok($id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return back();
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return back();
        }
    }


}
