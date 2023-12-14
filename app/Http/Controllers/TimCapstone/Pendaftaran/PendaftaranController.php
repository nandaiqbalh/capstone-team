<?php

namespace App\Http\Controllers\TimCapstone\Pendaftaran;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Pendaftaran\PendaftaranModel;
use Illuminate\Support\Facades\DB;
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
        $rs_topik_prioritas = PendaftaranModel::GetTopikPrioritas();
        // data
        $data = [
            'rs_pendaftaran' => $rs_pendaftaran,
            'rs_topik' => $rs_topik,
            'rs_topik_prioritas' => $rs_topik_prioritas
        ];
        // dd($data);
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
        $user_id = $request->user_id;
        $get_topik = PendaftaranModel::getTopikbyid($id_topik);
        $rs_mahasiswa = PendaftaranModel::getMahasiswa($id_topik);
        $rs_dosen = PendaftaranModel::getDosen($user_id);
        $rs_siklus = PendaftaranModel::getSiklusAktif();
        $data = [
            'rs_mahasiswa' =>  $rs_mahasiswa,
            'get_topik' =>  $get_topik,
            'rs_dosen' => $rs_dosen,
            'rs_siklus' => $rs_siklus
        ];
        // dd($data);
        // view
        return view('admin.pendaftaran.add', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateMhsTopikProcess(Request $request)
    {
        // authorize
        PendaftaranModel::authorize('U');

        // params
        $params = [
            'id_topik_mhs' => $request->id_topik,
            'status_individu' => 'disetujui',
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];
        // dd($params);
        // process
        if (PendaftaranModel::updateMhsTopik($request->user_id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/pendaftaran/');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/pendaftaran/' . $request->user_id);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPendaftaranProcess(Request $request)
    {

        // dd($request);
        // authorize
        PendaftaranModel::authorize('C');
        // params
        if ($request->id_dosen2 == $request->id_dosen1) {
            session()->flash('danger', 'Dosen tidak boleh sama!');
            return back()->withInput();
        }
        if ($request->id_mahasiswa1 == $request->id_mahasiswa2 || $request->id_mahasiswa1 == $request->id_mahasiswa3 || $request->id_mahasiswa2 == $request->id_mahasiswa3) {
            session()->flash('danger', 'Mahasiswa tidak boleh sama!');
            return back()->withInput();
        }
        $params = [
            'id_topik' => $request->id_topik,
            'nomor_kelompok' => $request->nomor_kelompok,
            'id_siklus' => $request->id_siklus,
            'judul_capstone' => $request->judul_ta,
            'id_dosen_pembimbing_1' => $request->id_dosen1,
            'id_dosen_pembimbing_2' => $request->id_dosen2,
            'status_kelompok' => 'disetujui',
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insertKelompok = PendaftaranModel::insertPendaftaranKelompok($params);
        if ($insertKelompok) {
            $id_kelompok = DB::getPdo()->lastInsertId();
            $paramMhs1 = [
                'id_kelompok' => $id_kelompok,
                'id_siklus' => $request->id_siklus,
                // 'id_mahasiswa' => $request->id_mahasiswa1,
                'id_topik_mhs' => $request->id_topik,
                'status_individu' => 'disetujui',
            ];
            PendaftaranModel::updateKelompokMHS($request->id_mahasiswa1, $paramMhs1);
            $paramMhs2 = [
                'id_kelompok' => $id_kelompok,
                'id_siklus' => $request->id_siklus,
                'id_topik_mhs' => $request->id_topik,
                'status_individu' => 'disetujui',
            ];
            PendaftaranModel::updateKelompokMHS($request->id_mahasiswa2, $paramMhs2);
            $paramMhs3 = [
                'id_kelompok' => $id_kelompok,
                'id_siklus' => $request->id_siklus,
                'id_topik_mhs' => $request->id_topik,
                'status_individu' => 'disetujui',
            ];
            PendaftaranModel::updateKelompokMHS($request->id_mahasiswa3, $paramMhs3);

            // insert dosen
            $paramDosen1 = [
                'id_kelompok' => $id_kelompok,
                'id_dosen' => $request->id_dosen1,
                'status_dosen' => 'menunggu persetujuan',
                'status_persetujuan' => 'menunggu persetujuan',
                'status_dosen' => 'pembimbing 1',
            ];
            PendaftaranModel::insertDosenKelompok($paramDosen1);

            $paramDosen2 = [
                'id_kelompok' => $id_kelompok,
                'id_dosen' => $request->id_dosen2,
                'status_persetujuan' => 'menunggu persetujuan',
                'status_dosen' => 'pembimbing 2',
            ];
            PendaftaranModel::insertDosenKelompok($paramDosen2);

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/kelompok');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/pendaftaran/add')->withInput();
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
