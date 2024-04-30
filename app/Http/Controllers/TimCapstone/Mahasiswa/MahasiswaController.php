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
        // Validate
        $rules = [
            'user_name' => 'required',
            'nomor_induk' => 'required|digits:14|unique:app_user,nomor_induk',
            'angkatan' => 'required|digits:4',
            'jenis_kelamin' => 'required',
        ];

        $messages = [
            'nomor_induk.digits' => 'NIM harus terdiri dari 14 digit angka.',
            'nomor_induk.unique' => 'NIM sudah digunakan oleh mahasiswa lain.',
            'angkatan.digits' => 'Angkatan harus terdiri dari 4 digit angka.',
            'no_telp.digits_between' => 'Nomor telepon harus terdiri antara 10-15 digit angka.'
        ];

        $this->validate($request, $rules, $messages);

        // params
        // default passwordnya mahasiswa123
        $user_id = MahasiswaModel::makeMicrotimeID();
        $params = [
            'user_id' => $user_id,
            'user_name' => $request->user_name,
            "nomor_induk" => $request->nomor_induk,
            "role_id" => '03',
            'user_email' => $request->user_email,
            'no_telp' => $request->no_telp,
            "angkatan" => $request->angkatan,
            'user_password' => Hash::make('mahasiswa123'),
            "jenis_kelamin" => $request->jenis_kelamin,
            'created_by' => Auth::user()->user_id,
            'created_date' => now()->format('Y-m-d H:i:s')
        ];

        // process
        $insert_mahasiswa = MahasiswaModel::insertmahasiswa($params);
        if ($insert_mahasiswa) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/tim-capstone/mahasiswa');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/tim-capstone/mahasiswa/add')->withInput();
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
       $mahasiswa -> status_tugas_akhir_color = $this->getStatusColor($mahasiswa->status_tugas_akhir);
       $mahasiswa -> status_kelompok_color = $this->getStatusColor($mahasiswa->status_kelompok);

       // check
       if (empty($mahasiswa)) {
           // flash message
           session()->flash('danger', 'Data tidak ditemukan.');
           return redirect('/tim-capstone/mahasiswa');
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
            return redirect('/tim-capstone/mahasiswa');
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
    // Validate
    $rules = [
        'user_name' => 'required',
        'nomor_induk' => 'required|digits:14|unique:app_user,nomor_induk,' . $request->user_id . ',user_id',
        'angkatan' => 'required|digits:4',
        'jenis_kelamin' => 'required',

    ];

    $messages = [
        'nomor_induk.required' => 'NIM wajib diisi.',
        'nomor_induk.digits' => 'NIM harus terdiri dari 14 digit angka.',
        'nomor_induk.unique' => 'NIM sudah digunakan oleh mahasiswa lain.',
        'angkatan.required' => 'Angkatan wajib diisi.',
        'angkatan.digits' => 'Angkatan harus terdiri dari 4 digit angka.',
        'jenis_kelamin.required' => 'Jenis Kelamin wajib dipilih.',
    ];

    $this->validate($request, $rules, $messages);

    // params
    $params = [
        'user_name' => $request->user_name,
        'nomor_induk' => $request->nomor_induk,
        'angkatan' => $request->angkatan,
        'jenis_kelamin' => $request->jenis_kelamin,
        'user_email' => $request->user_email,
        'no_telp' => $request->no_telp,
        'modified_by' => Auth::user()->user_id,
        'modified_date' => now()->format('Y-m-d H:i:s')
    ];

    // process
    if (MahasiswaModel::update($request->user_id, $params)) {
        // flash message
        session()->flash('success', 'Data berhasil disimpan.');
        return redirect('/tim-capstone/mahasiswa');
    } else {
        // flash message
        session()->flash('danger', 'Data gagal disimpan.');
        return redirect('/tim-capstone/mahasiswa/edit/' . $request->user_id);
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
                return redirect('/tim-capstone/mahasiswa');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/tim-capstone/settings/contoh-halaman');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/tim-capstone/settings/contoh-halaman');
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
        $user_name = $request->user_name;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_mahasiswa = MahasiswaModel::getDataSearch($user_name);
            // dd($rs_mahasiswa);
            // data
            $data = ['rs_mahasiswa' => $rs_mahasiswa, 'user_name' => $user_name];
            // view
            return view('tim_capstone.mahasiswa.index', $data);
        } else {
            return redirect('/tim-capstone/mahasiswa');
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
