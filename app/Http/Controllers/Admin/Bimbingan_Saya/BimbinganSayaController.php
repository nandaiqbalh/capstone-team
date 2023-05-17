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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
   

       
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailMahasiswa($user_id)
    {
        // authorize
        BimbinganSayaModel::authorize('R');

        // get data with pagination
        $mahasiswa = BimbinganSayaModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('admin.mahasiswa.detail', $data);
    }

   

    /**
     * Update the specified resource in storage. update status dosen 1
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function terimaBimbinganSaya1($id)
    {
        // authorize
        BimbinganSayaModel::authorize('U');

        ;

        // params
        $params = [
            'status_dosen_1' => 'distujui',
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
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
