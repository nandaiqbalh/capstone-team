<?php

namespace App\Http\Controllers\TimCapstone\Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Mahasiswa\MahasiswaModel;
use Illuminate\Support\Facades\Hash;


class MahasiswaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {

        // get data with pagination
        $rs_mahasiswa = MahasiswaModel::getDataWithPagination();
        // data
        $data = ['rs_mahasiswa' => $rs_mahasiswa];
        // view
        return view('tim_capstone.mahasiswa.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addMahasiswa()
    {

        // view
        return view('tim_capstone.mahasiswa.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addMahasiswaProcess(Request $request)
    {

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            "nim" => 'required',
            // "angkatan" => 'required',
            // "ipk" => 'required',
            // "sks" => 'required',
        ];
        $this->validate($request, $rules);


        // params
        // default passwordnya mahasiswa123
        $user_id = MahasiswaModel::makeMicrotimeID();
        $params = [
            'user_id' => $user_id,
            'user_name' => $request->nama,
            "nomor_induk" => $request->nim,
            "role_id" => '03',
            // 'user_email' => $request->email,
            // "angkatan" => $request->angkatan,
            // "ipk" => $request->ipk,
            // "sks" => $request->sks,
            'user_password' => Hash::make('mahasiswa123'),
            // "jenis_kelamin" => $request->jenis_kelamin,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];
        // dd($params);

        // process
        $insert_mahasiswa = MahasiswaModel::insertmahasiswa($params);
        if ($insert_mahasiswa) {

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


       // get data with pagination
       $mahasiswa = MahasiswaModel::getDataById($user_id);

       $mahasiswa -> status_individu_color = $this->getStatusColor($mahasiswa->status_individu);
       $mahasiswa -> status_kelompok_color = $this->getStatusColor($mahasiswa->status_kelompok);

       // check
       if (empty($mahasiswa)) {
           // flash message
           session()->flash('danger', 'Data tidak ditemukan.');
           return redirect('/admin/mahasiswa');
       }
       $rs_peminatan = MahasiswaModel::peminatanMahasiswa($user_id);

       foreach ($rs_peminatan as $key => $peminatan) {
           if ($peminatan->id == $mahasiswa->id_peminatan_individu1) {
               $peminatan->prioritas = "Prioritas 1";
           } else if($peminatan->id == $mahasiswa->id_peminatan_individu2) {
               $peminatan->prioritas = "Prioritas 2";
           }else if($peminatan->id == $mahasiswa->id_peminatan_individu3) {
               $peminatan->prioritas = "Prioritas 3";
           }else if($peminatan->id == $mahasiswa->id_peminatan_individu4) {
               $peminatan->prioritas = "Prioritas 4";
           } else {
               $peminatan->prioritas = "Belum memilih";

           }
       }
       // dd($mahasiswa);
       // data
       $data = [
           'mahasiswa' => $mahasiswa,
           'rs_peminatan'=>$rs_peminatan
       ];


        // view
        return view('tim_capstone.mahasiswa.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editMahasiswa($user_id)
    {
        // get data
        $mahasiswa = MahasiswaModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('tim_capstone.mahasiswa.edit', $data);
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

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            "nim" => 'required',
            // "angkatan" => 'required',
            // "ipk" => 'required',
            // "sks" => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'user_name' => $request->nama,
            "nomor_induk" => $request->nim,
            // 'user_email' => $request->email,
            // "angkatan" => $request->angkatan,
            // "ipk" => $request->ipk,
            // "sks" => $request->sks,
            // "jenis_kelamin" => $request->jenis_kelamin,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (MahasiswaModel::update($request->user_id, $params)) {
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

        // get data
        $mahasiswa = MahasiswaModel::getDataById($user_id);

        // if exist
        if (!empty($mahasiswa)) {
            // process
            if (MahasiswaModel::delete($user_id)) {
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
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_mahasiswa = MahasiswaModel::getDataSearch($user_name);
            // dd($rs_mahasiswa);
            // data
            $data = ['rs_mahasiswa' => $rs_mahasiswa, 'nama' => $user_name];
            // view
            return view('tim_capstone.mahasiswa.index', $data);
        } else {
            return redirect('/admin/mahasiswa');
        }
    }


public function getById($user_id)
{
    // Mengambil data mahasiswa berdasarkan user_id
    $mahasiswa = MahasiswaModel::getMahasiswaById($user_id);

    if ($mahasiswa) {
        // Mengembalikan respons JSON sukses jika data ditemukan
        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa ditemukan',
            'data' => $mahasiswa
        ]);
    } else {
        // Mengembalikan respons JSON error jika data tidak ditemukan
        return response()->json([
            'success' => false,
            'message' => 'Data mahasiswa tidak ditemukan',
            'data' => null
        ], 404); // 404 Not Found sebagai contoh status code
    }
}
}
