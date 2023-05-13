<?php

namespace App\Http\Controllers\Admin\Broadcast;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\Broadcast\BroadcastModel;
use Illuminate\Support\Facades\Hash;


class BroadcastController extends BaseController
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
        BroadcastModel::authorize('R');
        // dd(BroadcastModel::getData());

        // get data with pagination
        $rs_broadcast = BroadcastModel::getDataWithPagination();
        // data
        $data = ['rs_broadcast' => $rs_broadcast];
        // view
        return view('admin.broadcast.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addMahasiswa()
    {
        // authorize
        BroadcastModel::authorize('C');

        // view
        return view('admin.broadcast.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addMahasiswaProcess(Request $request)
    {

        // authorize
        BroadcastModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            "nim" => 'required',
            "angkatan" => 'required',
            "ipk" => 'required',
            "sks" => 'required',
            "alamat" => 'required',
        ];
        $this->validate($request, $rules);


        // params
        // default passwordnya mahasiswa123
        $user_id = BroadcastModel::makeMicrotimeID();
        $params = [
            'user_id' => $user_id,
            'user_name' => $request->nama,
            "nomor_induk" => $request->nim,
            "angkatan" => $request->angkatan,
            "ipk" => $request->ipk,
            "sks" => $request->sks,
            'user_password' => Hash::make('mahasiswa123'),
            "alamat" => $request->alamat,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insert_mahasiswa = BroadcastModel::insertmahasiswa($params);
        if ($insert_mahasiswa) {
            $params2 = [
                'user_id' => $user_id,
                'role_id' => '03'
            ];
            BroadcastModel::insertrole($params2);

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/mahasiswa');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/mahasiswa/add')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailMahasiswa($user_id)
    {
        // authorize
        BroadcastModel::authorize('R');

        // get data with pagination
        $mahasiswa = BroadcastModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('admin.broadcast.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editMahasiswa($user_id)
    {
        // authorize
        BroadcastModel::authorize('U');

        // get data 
        $mahasiswa = BroadcastModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('admin.broadcast.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editMahasiswaProcess(Request $request)
    {
        // authorize
        BroadcastModel::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            "nim" => 'required',
            "angkatan" => 'required',
            "ipk" => 'required',
            "sks" => 'required',
            "alamat" => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'user_name' => $request->nama,
            "nomor_induk" => $request->nim,
            "angkatan" => $request->angkatan,
            "ipk" => $request->ipk,
            "sks" => $request->sks,
            "alamat" => $request->alamat,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (BroadcastModel::update($request->user_id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/mahasiswa');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/mahasiswa/edit/' . $request->user_id);
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
        BroadcastModel::authorize('D');

        // get data
        $mahasiswa = BroadcastModel::getDataById($user_id);

        // if exist
        if (!empty($mahasiswa)) {
            // process
            if (BroadcastModel::delete($user_id)) {
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
        BroadcastModel::authorize('R');
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_broadcast = BroadcastModel::getDataSearch($user_name);
            // dd($rs_broadcast);
            // data
            $data = ['rs_broadcast' => $rs_broadcast, 'nama' => $user_name];
            // view
            return view('admin.broadcast.index', $data);
        } else {
            return redirect('/admin/mahasiswa');
        }
    }
}
