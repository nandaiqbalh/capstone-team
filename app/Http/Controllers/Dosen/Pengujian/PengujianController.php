<?php

namespace App\Http\Controllers\Dosen\Pengujian;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Dosen\Pengujian\PengujianModel;
use Illuminate\Support\Facades\Hash;


class PengujianController extends BaseController
{
    public function index()
    {
        // get data with pagination
        $rs_pengujian = PengujianModel::getDataWithPagination();

        // dd($rs_pengujian);
        // data
        $data = ['rs_pengujian' => $rs_pengujian];
        // view
        return view('dosen.pengujian.index', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailPengujian($id)
    {

        // get data with pagination
        $kelompok = PengujianModel::getDataById($id);
        $rs_mahasiswa = PengujianModel::getMahasiswa($kelompok->id);

        // check
        if (empty($kelompok)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/dosen/pengujian');
        }

        // data
        $data = [
            'kelompok' => $kelompok,
            'rs_mahasiswa' => $rs_mahasiswa,
        ];

        // view
        return view('dosen.pengujian.detail', $data);
    }



    /**
     * Update the specified resource in storage. update status dosen 1
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function terimaPengujian($id)
    {

        // params
        $params = [
            'status_persetujuan' => 'disetujui',
        ];

        // process
        if (PengujianModel::update($id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/pengujian');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/pengujian' . $request->id);
        }
    }
     /**
     * Update the specified resource in storage. update status dosen 2
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tolakPengujian($id)
    {

        // params
        $params = [
            'status_persetujuan' => 'tidak disetujui',
        ];

        // process
        if (PengujianModel::update($id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/pengujian');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/pengujian' . $request->id);
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
            $rs_ch = PengujianModel::getDataSearch($nama);
            // data
            $data = ['rs_ch' => $rs_ch, 'nama' => $nama];
            // view
            return view('dosen.settings.contoh-halaman.index', $data);
        } else {
            return redirect('/admin/settings/contoh-halaman');
        }
    }
}
