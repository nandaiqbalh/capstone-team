<?php

namespace App\Http\Controllers\Admin\Dosen;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\Dosen\DosenModel;
use Illuminate\Support\Facades\Hash;


class DosenController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        DosenModel::authorize('R');

        // get data with pagination
        $dt_dosen = DosenModel::getDataWithPagination();
        // data
        $data = ['dt_dosen' => $dt_dosen];
        // view
        return view('admin.dosen.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addDosen()
    {
        // authorize
        DosenModel::authorize('C');

        // view
        return view('admin.dosen.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDosenProcess(Request $request)
    {

        // authorize
        DosenModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            "nip" => 'required',
            // "alamat" => 'required',
        ];
        $this->validate($request, $rules);


        // params
        // default passwordnya mahasiswa123
        $user_id = DosenModel::makeMicrotimeID();
        $params = [
            'user_id' => $user_id,
            'user_name' => $request->nama,
            "nomor_induk" => $request->nip,
            'role_id' =>  $request->role_id,
            'user_password' => Hash::make('dosen12345'),
            // "alamat" => $request->alamat,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insert_dosen = DosenModel::insertdosen($params);
        if ($insert_dosen) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/dosen');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/dosen/add')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailDosen($id)
    {
        // authorize
        DosenModel::authorize('R');

        // get data with pagination
        $dosen = DosenModel::getDataById($id);

        // check
        if (empty($dosen)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/dosen');
        }

        // data
        $data = ['dosen' => $dosen];

        // view
        return view('admin.dosen.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDosen($user_id)
    {
        // authorize
        DosenModel::authorize('U');

        // get data
        $dosen = DosenModel::getDataById($user_id);

        // check
        if (empty($dosen)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/dosen');
        }

        // data
        $data = ['dosen' => $dosen];

        // view
        return view('admin.dosen.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDosenProcess(Request $request)
    {
        // authorize
        DosenModel::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            "nip" => 'required',
            // "alamat" => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'user_name' => $request->nama,
            "nomor_induk" => $request->nip,
            'role_id' =>  $request->role,
            // "alamat" => $request->alamat,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (DosenModel::update($request->user_id, $params)) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/dosen');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/dosen/edit/' . $request->user_id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteDosenProcess($id)
    {
        // authorize
        DosenModel::authorize('D');

        // get data
        $dosen = DosenModel::getDataById($id);

        // if exist
        if (!empty($dosen)) {
            // process
            if (DosenModel::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/dosen');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/dosen');
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
    public function searchDosen(Request $request)
    {
        // authorize
        DosenModel::authorize('R');
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $dt_dosen = DosenModel::getDataSearch($user_name);
            // dd($rs_mahasiswa);
            // data
            $data = ['dt_dosen' => $dt_dosen, 'nama' => $user_name];
            // view
            return view('admin.dosen.index', $data);
        } else {
            return redirect('/admin/dosen');
        }
    }
}
