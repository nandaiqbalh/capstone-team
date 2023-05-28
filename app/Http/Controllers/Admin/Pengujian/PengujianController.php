<?php

namespace App\Http\Controllers\Admin\Pengujian;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\Pengujian\PengujianModel;
use Illuminate\Support\Facades\Hash;


class PengujianController extends BaseController
{
    public function index()
    {
        // authorize
        PengujianModel::authorize('R');
        // dd(PengujianModel::getData());

        // get data with pagination
        $rs_pengujian = PengujianModel::getDataWithPagination();
        // data
        $data = ['rs_pengujian' => $rs_pengujian];
        // view
        return view('admin.pengujian.index', $data);
    }
       
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailPengujian($id)
    {
        // authorize
        PengujianModel::authorize('R');

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
        return view('admin.pengujian.detail', $data);
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
        // authorize
        PengujianModel::authorize('U');

        ;

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
        // authorize
        PengujianModel::authorize('U');

        ;

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
        // authorize
        PengujianModel::authorize('R');

        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_ch = PengujianModel::getDataSearch($nama);
            // data
            $data = ['rs_ch' => $rs_ch, 'nama' => $nama];
            // view
            return view('admin.settings.contoh-halaman.index', $data);
        } else {
            return redirect('/admin/settings/contoh-halaman');
        }
    }
}
