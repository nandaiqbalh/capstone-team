<?php

namespace App\Http\Controllers\Admin\Pendaftaran;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\Pendaftaran\PendaftaranModel;
use Illuminate\Support\Facades\Hash;


class PendaftaranController extends BaseController
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
        PendaftaranModel::authorize('R');
        // dd(PendaftaranModel::getData());

        // get data with pagination
        $rs_pendaftaran = PendaftaranModel::getDataWithPagination();
        $rs_topik = PendaftaranModel::GetTopik();
        // data
        $data = [
            'rs_pendaftaran' => $rs_pendaftaran,
            'rs_topik' => $rs_topik
        ];

        // view
        return view('admin.pendaftaran.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addPendaftaran(Request $request)
    {
        // authorize
        PendaftaranModel::authorize('C');
        $id_topik = $request->id_topik;
        $rs_mahasiswa = PendaftaranModel::getMahasiswa($id_topik);
        $data = [
            'rs_mahasiswa' =>  $rs_mahasiswa,
            'id_topik' => $id_topik
        ];
        dd($data);
        // view
        return view('admin.pendaftaran.add', $data);
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
        PendaftaranModel::authorize('C');

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
        $user_id = PendaftaranModel::makeMicrotimeID();
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
        $insert_mahasiswa = PendaftaranModel::insertmahasiswa($params);
        if ($insert_mahasiswa) {
            $params2 = [
                'user_id' => $user_id,
                'role_id' => '03'
            ];
            PendaftaranModel::insertrole($params2);

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
        PendaftaranModel::authorize('R');

        // get data with pagination
        $mahasiswa = PendaftaranModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('admin.pendaftaran.detail', $data);
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
        PendaftaranModel::authorize('U');

        // get data 
        $mahasiswa = PendaftaranModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('admin.pendaftaran.edit', $data);
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
        PendaftaranModel::authorize('U');

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
        if (PendaftaranModel::update($request->user_id, $params)) {
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
        PendaftaranModel::authorize('D');

        // get data
        $mahasiswa = PendaftaranModel::getDataById($user_id);

        // if exist
        if (!empty($mahasiswa)) {
            // process
            if (PendaftaranModel::delete($user_id)) {
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
        PendaftaranModel::authorize('R');
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_pendaftaran = PendaftaranModel::getDataSearch($user_name);
            // dd($rs_pendaftaran);
            // data
            $data = ['rs_pendaftaran' => $rs_pendaftaran, 'nama' => $user_name];
            // view
            return view('admin.pendaftaran.index', $data);
        } else {
            return redirect('/admin/mahasiswa');
        }
    }
}
