<?php

namespace App\Http\Controllers\Admin\Expo_Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\Expo_Mahasiswa\MahasiswaExpoModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaExpoController extends BaseController
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
        MahasiswaExpoModel::authorize('R');
        $cekExpo = MahasiswaExpoModel::cekExpo();
        // dd($cekExpo, Auth::user()->user_id);

        $id_kelompok = MahasiswaExpoModel::idKelompok(Auth::user()->user_id);
        // get data expo
        $rs_expo = MahasiswaExpoModel::getDataExpo();
        $kelengkapanExpo = MahasiswaExpoModel::kelengkapanExpo();

        // data
        $data = [
            'id_kelompok' => $id_kelompok,
            'cekExpo' => $cekExpo,
            'rs_expo' => $rs_expo,
            'kelengkapan'=>$kelengkapanExpo
        ];

        // view
        return view('admin.expo-mahasiswa.detail', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function daftar(Request $request,$id)
    {
        // authorize
        MahasiswaExpoModel::authorize('U');

        // params
        $params = [
            'id_kelompok' => $request->id_kelompok,
            'id_expo' => $id,
            'status' => 'menunggu persetujuan',
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (MahasiswaExpoModel::insertIDKelompok($params)) {
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMahasiswaProcess($user_id)
    {
        // authorize
        MahasiswaExpoModel::authorize('D');

        // get data
        $mahasiswa = MahasiswaExpoModel::getDataById($user_id);

        // if exist
        if (!empty($mahasiswa)) {
            // process
            if (MahasiswaExpoModel::delete($user_id)) {
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
    public function searchMahasiswa(Request $request)
    {
        // authorize
        MahasiswaExpoModel::authorize('R');
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_mahasiswa = MahasiswaExpoModel::getDataSearch($user_name);
            // dd($rs_mahasiswa);
            // data
            $data = ['rs_mahasiswa' => $rs_mahasiswa, 'nama' => $user_name];
            // view
            return view('admin.mahasiswa.index', $data);
        } else {
            return redirect('/admin/mahasiswa');
        }
    }

    public function editProcess(Request $request)
    {
        // authorize
        // dd($request);
        MahasiswaExpoModel::authorize('D');
        $id_kelompok = $request->id;
        // get data
        $kelompok = MahasiswaExpoModel::getDataById($id_kelompok);

        // if exist
        if (!empty($kelompok)) {
            // process
            $params = [
                'judul_ta_mhs' => $request->judul_ta_mhs,
                'link_upload' => $request->link_upload,

            ];
            if (MahasiswaExpoModel::updateKelompokMHS($id_kelompok, $params)) {
                // flash message
                session()->flash('success', 'Data berhasil disimpan.');
                return back();
            } else {
                // flash message
                session()->flash('danger', 'Data sudah ada.');
                return back();
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return back();
        }
    }
}
