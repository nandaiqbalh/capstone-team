<?php

namespace App\Http\Controllers\Admin\Kelompok_Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\Kelompok_Mahasiswa\MahasiswaKelompokModel;
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
        // authorize
        MahasiswaKelompokModel::authorize('R');

        // get data kelompok
        $kelompok = MahasiswaKelompokModel::pengecekan_kelompok_mahasiswa();
        $rs_siklus = MahasiswaKelompokModel::getSiklusAktif();
        if ($kelompok != null) {
            $rs_mahasiswa = MahasiswaKelompokModel::listKelompokMahasiswa($kelompok->id_kelompok);
            $rs_dosbing = MahasiswaKelompokModel::getAkunDosbingKelompok($kelompok->id_kelompok);
            $rs_dospeng = MahasiswaKelompokModel::getAkunDospengKelompok($kelompok->id_kelompok);
            $proposal = MahasiswaKelompokModel::proposal($kelompok->id_kelompok);

            // data
            $data = [
                'kelompok'  => $kelompok,
                'proposal'  => $proposal,
                'rs_mahasiswa' => $rs_mahasiswa,
                'rs_dosbing' => $rs_dosbing,
                'rs_dospeng' => $rs_dospeng,
                'rs_siklus' => $rs_siklus,
            ];
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
        return view('admin.kelompok-mahasiswa.detail', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addKelompokProcess(Request $request)
    {
        // dd($request['angkatan']);

        // authorize
        MahasiswaKelompokModel::authorize('C');

        // params
        $params = [
            // 'user_id' => Auth::user()->user_id,
            "angkatan" => $request->angkatan,
            "user_email" => $request->email,
            "jenis_kelamin" => $request->jenis_kelamin,
            "ipk" => $request->ipk,
            "sks" => $request->sks,
            'no_telp' => $request->no_telp,
            "alamat" => $request->alamat,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $update_mahasiswa = MahasiswaKelompokModel::updateMahasiswa(Auth::user()->user_id, $params);
        if ($update_mahasiswa) {
            $params2 = [
                "id_siklus" => $request->id_siklus,
                'id_mahasiswa' => Auth::user()->user_id,
                'status_individu' => 'menunggu persetujuan',
            ];
            MahasiswaKelompokModel::insertKelompokMHS($params2);
            $insert_id = DB::getPdo()->lastInsertId();

            $paramS = [
                'id_mahasiswa' => Auth::user()->user_id,
                'id_kel_mhs' => $insert_id,
                'peminatan' => 'Software & Database',
                'prioritas' => $request->s,
            ];

            MahasiswaKelompokModel::insertPeminatan($paramS);

            $paramE = [
                'id_mahasiswa' => Auth::user()->user_id,
                'id_kel_mhs' => $insert_id,
                'peminatan' => 'Embedded System & Robotics',
                'prioritas' => $request->e,
            ];

            MahasiswaKelompokModel::insertPeminatan($paramE);

            $paramC = [
                'id_mahasiswa' => Auth::user()->user_id,
                'id_kel_mhs' => $insert_id,
                'peminatan' => 'Computer Network & Security',
                'prioritas' => $request->c,
            ];

            MahasiswaKelompokModel::insertPeminatan($paramC);

            $paramM = [
                'id_mahasiswa' => Auth::user()->user_id,
                'id_kel_mhs' => $insert_id,
                'peminatan' => 'Multimedia & Game',
                'prioritas' => $request->m,
            ];

            MahasiswaKelompokModel::insertPeminatan($paramM);

            $rs_topik = MahasiswaKelompokModel::getTopik();
            foreach ($rs_topik as $key => $value) {
                $param = [
                    'id_mahasiswa'  => Auth::user()->user_id,
                    'id_kel_mhs'    => $insert_id,
                    'id_topik'     => $value->id,
                    'prioritas' => $request[$value->id],
                    'created_by'   => Auth::user()->user_id,
                    'created_date'  => date('Y-m-d H:i:s')
                ];

                MahasiswaKelompokModel::insertTopikMHS($param);
            }

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/mahasiswa/kelompok');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/mahasiswa/add')->withInput();
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

        // dd($request);
        // authorize
        MahasiswaKelompokModel::authorize('C');
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
            "judul_ta" => $request->judul_ta,
            "id_topik" => $request->id_topik,
            "status_kelompok" => "menunggu persetujuan",
            "id_dosen_pembimbing_1" => $request->dosbing_1,
            "id_dosen_pembimbing_2" => $request->dosbing_2,
        ];
        MahasiswaKelompokModel::insertKelompok($params);
        $id_kelompok = DB::getPdo()->lastInsertId();

        $paramsDosen1 = [
            "id_kelompok" => $id_kelompok,
            "id_dosen" => $request->dosbing_1,
            "status_dosen" => "pembimbing 1",
            "status_persetujuan" => "menunggu persetujuan",
        ];
        MahasiswaKelompokModel::insertDosenKelompok($paramsDosen1);
        $paramsDosen2 = [
            "id_kelompok" => $id_kelompok,
            "id_dosen" => $request->dosbing_2,
            "status_dosen" => "pembimbing 2",
            "status_persetujuan" => "menunggu persetujuan",
        ];
        MahasiswaKelompokModel::insertDosenKelompok($paramsDosen2);


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
                'id_topik_mhs' => $request->id_topik,
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
                'id_topik_mhs' => $request->id_topik,
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
                'id_topik_mhs' => $request->id_topik,
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
        // authorize
        MahasiswaKelompokModel::authorize('R');

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
        return view('admin.mahasiswa.detail', $data);
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
        MahasiswaKelompokModel::authorize('U');

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
        return view('admin.mahasiswa.edit', $data);
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
        MahasiswaKelompokModel::authorize('U');

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
        // authorize
        MahasiswaKelompokModel::authorize('D');

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
        // authorize
        MahasiswaKelompokModel::authorize('R');
        // data request
        $user_name = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_mahasiswa = MahasiswaKelompokModel::getDataSearch($user_name);
            // dd($rs_mahasiswa);
            // data
            $data = ['rs_mahasiswa' => $rs_mahasiswa, 'nama' => $user_name];
            // view
            return view('admin.mahasiswa.index', $data);
        } else {
            return redirect('/admin/mahasiswa');
        }
    }
}
