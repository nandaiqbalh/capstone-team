<?php

namespace App\Http\Controllers\Admin\Bimbingan_Saya;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\Bimbingan_Saya\BimbinganSayaModel;
use Illuminate\Support\Facades\Hash;


class BimbinganSayaController extends BaseController
{
    

    public function index()
    {
        // authorize
        BimbinganSayaModel::authorize('R');
        // dd(BimbinganSayaModel::getData());

        // get data with pagination
        $rs_bimbingan_saya = BimbinganSayaModel::getDataWithPagination();
        // data
        $data = ['rs_bimbingan_saya' => $rs_bimbingan_saya];
        // view
        return view('admin.bimbingan-saya.index', $data);
    }

  
   

       
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailBimbinganSaya($id)
    {
        // authorize
        BimbinganSayaModel::authorize('R');

        // get data with pagination
        $kelompok = BimbinganSayaModel::getDataById($id);

        // check
        if (empty($kelompok)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/dosen/bimbingan-saya');
        }

        // data
        $data = ['kelompok' => $kelompok];

        // view
        return view('admin.bimbingan-saya.detail', $data);
    }

   

    /**
     * Update the specified resource in storage. update status dosen 1
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function terimaBimbinganSaya($id)
    {
        // authorize
        BimbinganSayaModel::authorize('U');

        ;

        // params
        $params = [
            'status_persetujuan' => 'disetujui',
        ];

        // process
        if (BimbinganSayaModel::update($id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/bimbingan-saya');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/bimbingan-saya' . $request->id);
        }
    }
     /**
     * Update the specified resource in storage. update status dosen 2
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tolakBimbinganSaya($id)
    {
        // authorize
        BimbinganSayaModel::authorize('U');

        ;

        // params
        $params = [
            'status_persetujuan' => 'tidak disetujui',
        ];

        // process
        if (BimbinganSayaModel::update($id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/dosen/bimbingan-saya');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/dosen/bimbingan-saya' . $request->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMahasiswaProcess($user_id)
    {
        // authorize
        BimbinganSayaModel::authorize('D');

        // get data
        $mahasiswa = BimbinganSayaModel::getDataById($user_id);

        // if exist
        if (!empty($mahasiswa)) {
            // process
            if (BimbinganSayaModel::delete($user_id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/mahasiswa');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/settings/contoh-halaman');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/contoh-halaman');
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
        BimbinganSayaModel::authorize('R');

        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_ch = BimbinganSayaModel::getDataSearch($nama);
            // data
            $data = ['rs_ch' => $rs_ch, 'nama' => $nama];
            // view
            return view('admin.settings.contoh-halaman.index', $data);
        } else {
            return redirect('/admin/settings/contoh-halaman');
        }
    }
}
