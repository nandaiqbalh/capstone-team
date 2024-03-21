<?php

namespace App\Http\Controllers\Mahasiswa\Kelompok_Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Mahasiswa\Kelompok_Mahasiswa\MahasiswaKelompokModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDO;

class MahasiswaKelompokController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {


        // get data kelompok
        $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa();
        $rs_siklus = MahasiswaKelompokModel::getSiklusAktif();
        if ($kelompok != null) {
            $rs_mahasiswa = MahasiswaKelompokModel::listKelompokMahasiswa($kelompok->id_kelompok);
            $rs_dosbing = MahasiswaKelompokModel::getAkunDosbingKelompok($kelompok->id_kelompok);
            $rs_dospeng = MahasiswaKelompokModel::getAkunDospengKelompok($kelompok->id_kelompok);
            $proposal = MahasiswaKelompokModel::proposal($kelompok->id_kelompok);

            foreach ($rs_dosbing as $dosbing) {

                if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_1) {
                    $dosbing->jenis_dosen = 'Pembimbing 1';
                    $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_1;
                } else if ($dosbing->user_id == $kelompok->id_dosen_pembimbing_2) {
                    $dosbing->jenis_dosen = 'Pembimbing 2';
                    $dosbing->status_dosen = $kelompok->status_dosen_pembimbing_2;
                }

            }

            // dd($rs_dosbing);
            // data
            $data = [
                'kelompok'  => $kelompok,
                'proposal'  => $proposal,
                'rs_mahasiswa' => $rs_mahasiswa,
                'rs_dosbing' => $rs_dosbing,
                'rs_dospeng' => $rs_dospeng,
                'rs_siklus' => $rs_siklus,
            ];


            // dd($data);
        } else {
            $getAkun = MahasiswaKelompokModel::getAkunByID(Auth::user()->user_id);
            $rs_mahasiswa = MahasiswaKelompokModel::getAkun();
            $rs_dosbing = MahasiswaKelompokModel::getAkunDosen();
            $rs_topik = MahasiswaKelompokModel::getTopik();
            // data
            $data = [
                'rs_topik' => $rs_topik,
                'kelompok'  => $kelompok,
                'getAkun' => $getAkun,
                'rs_mahasiswa' => $rs_mahasiswa,
                'rs_dosbing' => $rs_dosbing,
                'rs_siklus' => $rs_siklus,
            ];
        }

        // dd($data);
        // view
        return view('mahasiswa.kelompok-mahasiswa.detail', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function addKelompokProcess(Request $request)
     {

         $user = MahasiswaKelompokModel::getAkunBelumPunyaKelompok(Auth::user() -> user_id);

         // Check if the user exists
         if ($user != null && $user->user_active == 1) {
             // validation params
             $requiredParams = ['angkatan', 'email', 'jenis_kelamin', 'ipk', 'sks', 'no_telp', 'id_siklus', 'peminatan', 'topik'];
             foreach ($requiredParams as $param) {
                 if (!$request->has($param) || empty($request->input($param))) {
                    session()->flash('danger', "Parameter '$param' kosong atau belum diisi!");
                    return back()->withInput();

                }
             }

             try {
                 // params
                 $params = [
                     "angkatan" => $request->angkatan,
                     "user_name" => Auth::user()->user_name,
                     "nomor_induk" => Auth::user()->nomor_induk,
                     "user_email" => $request->email,
                     "jenis_kelamin" => $request->jenis_kelamin,
                     "ipk" => $request->ipk,
                     "sks" => $request->sks,
                     'no_telp' => $request->no_telp,
                     'modified_by'   => Auth::user()->user_id,
                     'modified_date'  => now(),
                 ];

                 // process
                 $update_mahasiswa = MahasiswaKelompokModel::updateMahasiswa($user->user_id, $params);

                 if ($update_mahasiswa) {

                    $peminatanEntered = $request ->peminatan;
                    $peminatanArray = array_map('trim', explode(',', $peminatanEntered));

                    $peminatan1 = MahasiswaKelompokModel::getPeminatanById($peminatanArray[0]);
                    $peminatan2 = MahasiswaKelompokModel::getPeminatanById($peminatanArray[1]);
                    $peminatan3 = MahasiswaKelompokModel::getPeminatanById($peminatanArray[2]);
                    $peminatan4 = MahasiswaKelompokModel::getPeminatanById($peminatanArray[3]);

                    $topikEntered = $request ->topik;
                    $topikArray = array_map('trim', explode(',', $topikEntered));

                    $topik1 = MahasiswaKelompokModel::getTopikById($topikArray[0]);
                    $topik2 = MahasiswaKelompokModel::getTopikById($topikArray[1]);
                    $topik3 = MahasiswaKelompokModel::getTopikById($topikArray[2]);
                    $topik4 = MahasiswaKelompokModel::getTopikById($topikArray[3]);

                     // Insert kelompok mhs
                     $params2 = [
                         'usulan_judul_capstone' => $request->judul_capstone,
                         'id_siklus' => $request->id_siklus,
                         'id_mahasiswa' => $user->user_id,
                         'status_individu' => 'Menunggu Validasi Kelompok!',
                         'id_topik_individu1' => $topik1->id,
                         'id_topik_individu2' => $topik2->id,
                         'id_topik_individu3' => $topik3->id,
                         'id_topik_individu4' => $topik4->id,
                         'id_peminatan_individu1' => $peminatan1->id,
                         'id_peminatan_individu2' => $peminatan2->id,
                         'id_peminatan_individu3' => $peminatan3->id,
                         'id_peminatan_individu4' => $peminatan4->id,
                         'created_by'   => Auth::user()->user_id,
                         'created_date'  => now(),
                     ];

                     MahasiswaKelompokModel::insertKelompokMHS($params2);

                    // flash message
                    session()->flash('success', 'Berhasil mendaftar capstone!');
                    return redirect('/mahasiswa/kelompok');
                 } else {
                     session()->flash('danger', 'Pengguna tidak ditemukan!');
                    return back()->withInput();

                 }
             } catch (\Exception $e) {
                 session()->flash('danger', 'Gagal mendaftar capstone!');
                 dd($e);
                return back()->withInput();

             }
         } else {
            session()->flash('danger', 'Pengguna tidak ditemukan!');
            return back()->withInput();

         }
     }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPunyaKelompokProcess(Request $request)
    {

        if ($request->dosbing_1 == $request->dosbing_2) {
            session()->flash('danger', 'Dosen tidak boleh sama!');
            return back()->withInput();
        }
        if ($request->nama1 == $request->nama2 || $request->nama1 == $request->nama3 || $request->nama2 == $request->nama3) {
            session()->flash('danger', 'Mahasiswa tidak boleh sama!');
            return back()->withInput();
        }

        // addKelompok

        $params = [
            "id_siklus" => $request->id_siklus,
            "judul_capstone" => $request->judul_capstone,
            "id_topik" => $request->id_topik,
            "status_kelompok" => "menunggu persetujuan",
            "id_dosen_pembimbing_1" => $request->dosbing_1,
            "id_dosen_pembimbing_2" => $request->dosbing_2,
            "status_dosen_pembimbing_1" => "menunggu persetujuan",
            "status_dosen_pembimbing_2" => "menunggu persetujuan",

        ];
        MahasiswaKelompokModel::insertKelompok($params);
        $id_kelompok = DB::getPdo()->lastInsertId();

        // params mahasiswa 1
        $params1 = [
            "angkatan" => $request->angkatan1,
            "ipk" => $request->ipk1,
            "user_email" => $request->email1,
            "jenis_kelamin" => $request->jenis_kelamin1,
            "sks" => $request->sks1,
            'no_telp' => $request->no_telp1,
            "alamat" => $request->alamat1,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $update_mahasiswa1 = MahasiswaKelompokModel::updateMahasiswa(Auth::user()->user_id, $params1);
        if ($update_mahasiswa1) {
            $params11 = [
                "id_siklus" => $request->id_siklus,
                'id_kelompok' => $id_kelompok,
                'id_mahasiswa' => Auth::user()->user_id,
                'status_individu' => 'menunggu persetujuan',
                'usulan_judul_capstone' => $request -> judul_capstone,
                'id_topik_mhs' => $request->id_topik,
                'created_by'   => Auth::user()->user_id,
                'created_date'  => date('Y-m-d H:i:s')
            ];
            MahasiswaKelompokModel::insertKelompokMHS($params11);
        }

        // params mahasiswa 2
        $params2 = [
            // 'user_id' => Auth::user()->user_id,
            "angkatan" => $request->angkatan2,
            "user_email" => $request->email2,
            "jenis_kelamin" => $request->jenis_kelamin2,
            "ipk" => $request->ipk2,
            "sks" => $request->sks2,
            'no_telp' => $request->no_telp2,
            "alamat" => $request->alamat2,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $update_mahasiswa2 = MahasiswaKelompokModel::updateMahasiswa($request->nama2, $params2);
        if ($update_mahasiswa2) {
            $params22 = [
                "id_siklus" => $request->id_siklus,
                'id_kelompok' => $id_kelompok,
                'id_mahasiswa' => $request->nama2,
                'status_individu' => 'menunggu persetujuan',
                'usulan_judul_capstone' => $request -> judul_capstone,
                'id_topik_mhs' => $request->id_topik,
                'created_by'   => Auth::user()->user_id,
                'created_date'  => date('Y-m-d H:i:s')
            ];
            MahasiswaKelompokModel::insertKelompokMHS($params22);
        }

        // params mahasiswa 3
        $params3 = [
            // 'user_id' => Auth::user()->user_id,
            "angkatan" => $request->angkatan3,
            "user_email" => $request->email3,
            "jenis_kelamin" => $request->jenis_kelamin3,
            "ipk" => $request->ipk3,
            "sks" => $request->sks3,
            'no_telp' => $request->no_telp3,
            "alamat" => $request->alamat3,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $update_mahasiswa3 = MahasiswaKelompokModel::updateMahasiswa($request->nama3, $params3);
        if ($update_mahasiswa3) {
            $params33 = [
                "id_siklus" => $request->id_siklus,
                'id_kelompok' => $id_kelompok,
                'id_mahasiswa' => $request->nama3,
                'status_individu' => 'menunggu persetujuan',
                'usulan_judul_capstone' => $request -> judul_capstone,
                'id_topik_mhs' => $request->id_topik,
                'created_by'   => Auth::user()->user_id,
                'created_date'  => date('Y-m-d H:i:s')
            ];
            MahasiswaKelompokModel::insertKelompokMHS($params33);
        }
        return redirect('/mahasiswa/kelompok');
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
        $mahasiswa = MahasiswaKelompokModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('mahasiswa.mahasiswa.detail', $data);
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
        $mahasiswa = MahasiswaKelompokModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('mahasiswa.mahasiswa.edit', $data);
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
        if (MahasiswaKelompokModel::update($request->user_id, $params)) {
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
        $mahasiswa = MahasiswaKelompokModel::getDataById($user_id);

        // if exist
        if (!empty($mahasiswa)) {
            // process
            if (MahasiswaKelompokModel::delete($user_id)) {
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

        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_mahasiswa = MahasiswaKelompokModel::getDataSearch($user_name);
            // dd($rs_mahasiswa);
            // data
            $data = ['rs_mahasiswa' => $rs_mahasiswa, 'nama' => $user_name];
            // view
            return view('mahasiswa.mahasiswa.index', $data);
        } else {
            return redirect('/admin/mahasiswa');
        }
    }
}
