<?php

namespace App\Http\Controllers\TimCapstone\PenetapanKelompok;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\PenetapanKelompok\PenetapanKelompokModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\TimCapstone\Mahasiswa\MahasiswaModel;


class PenetapanKelompokController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //coba comment

    public function index()
    {

        $rs_mahasiswa = MahasiswaModel::getDataWithPagination();
        $rs_pendaftaran = PenetapanKelompokModel::getDataWithPagination();
        $rs_topik = PenetapanKelompokModel::getTopik();
        $rs_peminatan = PenetapanKelompokModel::getPeminatan();

        foreach ($rs_pendaftaran as $key => $mahasiswa) {
            foreach ($rs_topik as $key => $topik) {
                if ($topik->id == $mahasiswa->id_topik_individu1) {
                    $mahasiswa->prioritas_topik = $topik->nama;
                    break; // Exit the loop once the first match is found
                } else {
                    $mahasiswa->prioritas_topik = "Belum memilih";
                }
            }

            foreach ($rs_peminatan as $key => $peminatan) {
                if ($peminatan->id == $mahasiswa->id_peminatan_individu1) {
                    $mahasiswa->prioritas_peminatan = $peminatan->nama_peminatan;
                    break; // Exit the loop once the first match is found
                } else {
                    $mahasiswa->prioritas_peminatan = "Belum memilih";
                }
            }
        }

        // data
        $data = [
            'rs_pendaftaran' => $rs_pendaftaran,
            'rs_topik' => $rs_topik,
        ];

        // view
        return view('tim_capstone.penetapan-kelompok.index', $data);
    }

    // masuk ke halaman plotting kelompok
    public function addPenetapanKelompok(Request $request)
    {
        $id_topik = $request->id_topik;
        $user_id = $request->user_id;
        $get_topik = PenetapanKelompokModel::getTopikbyid($id_topik);
        $rs_mahasiswa = PenetapanKelompokModel::getMahasiswa($id_topik);
        $rs_siklus = PenetapanKelompokModel::getSiklusAktif();

        // dd($rs_mahasiswa);
        $data = [
            'rs_mahasiswa' =>  $rs_mahasiswa,
            'get_topik' =>  $get_topik,
            'rs_siklus' => $rs_siklus
        ];
        // view
        return view('tim_capstone.penetapan-kelompok.add', $data);
    }

    // proses pengelompokan mahasiswa
    public function addPenetapanKelompokProcess(Request $request)
    {

        if ($request->id_mahasiswa1 == $request->id_mahasiswa2 || $request->id_mahasiswa1 == $request->id_mahasiswa3 || $request->id_mahasiswa2 == $request->id_mahasiswa3) {
            session()->flash('danger', 'Mahasiswa tidak boleh sama!');
            return back()->withInput();
        }
        $params = [
            'id_topik' => $request->id_topik,
            'id_siklus' => $request->id_siklus,
            'status_kelompok' => 'Menunggu Persetujuan Dosbing!',
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insertKelompok = PenetapanKelompokModel::insertPendaftaranKelompok($params);
        if ($insertKelompok) {
            $id_kelompok = DB::getPdo()->lastInsertId();
            $paramMhs1 = [
                'id_kelompok' => $id_kelompok,
                'id_siklus' => $request->id_siklus,
                'id_topik_mhs' => $request->id_topik,
                'status_individu' => 'Menyetujui Kelompok!',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
            PenetapanKelompokModel::updateKelompokMHS($request->id_mahasiswa1, $paramMhs1);
            $paramMhs2 = [
                'id_kelompok' => $id_kelompok,
                'id_siklus' => $request->id_siklus,
                'id_topik_mhs' => $request->id_topik,
                'status_individu' => 'Menyetujui Kelompok!',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
            PenetapanKelompokModel::updateKelompokMHS($request->id_mahasiswa2, $paramMhs2);
            $paramMhs3 = [
                'id_kelompok' => $id_kelompok,
                'id_siklus' => $request->id_siklus,
                'id_topik_mhs' => $request->id_topik,
                'status_individu' => 'Menyetujui Kelompok!',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
            PenetapanKelompokModel::updateKelompokMHS($request->id_mahasiswa3, $paramMhs3);

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/penetapan-dosbing');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/penetapan-kelompok/add')->withInput();
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
        $mahasiswa = PenetapanKelompokModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('tim_capstone.penetapan-kelompok.detail', $data);
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
        $mahasiswa = PenetapanKelompokModel::getDataById($user_id);

        // check
        if (empty($mahasiswa)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/mahasiswa');
        }

        // data
        $data = ['mahasiswa' => $mahasiswa];

        // view
        return view('tim_capstone.penetapan-kelompok.edit', $data);
    }

    public function searchMahasiswa(Request $request)
    {
        // data request
        $user_name = $request->nama;



        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_pendaftaran = PenetapanKelompokModel::getDataSearch($user_name);
            $rs_mahasiswa = MahasiswaModel::getDataWithPagination();
            $rs_topik = PenetapanKelompokModel::getTopik();
            $rs_peminatan = PenetapanKelompokModel::getPeminatan();

            foreach ($rs_pendaftaran as $key => $mahasiswa) {
                foreach ($rs_topik as $key => $topik) {
                    if ($topik->id == $mahasiswa->id_topik_individu1) {
                        $mahasiswa->prioritas_topik = $topik->nama;
                        break; // Exit the loop once the first match is found
                    } else {
                        $mahasiswa->prioritas_topik = "Belum memilih";
                    }
                }

                foreach ($rs_peminatan as $key => $peminatan) {
                    if ($peminatan->id == $mahasiswa->id_peminatan_individu1) {
                        $mahasiswa->prioritas_peminatan = $peminatan->nama_peminatan;
                        break; // Exit the loop once the first match is found
                    } else {
                        $mahasiswa->prioritas_peminatan = "Belum memilih";
                    }
                }
            }

            // data
            $data = [
                'rs_pendaftaran' => $rs_pendaftaran,
                'nama' => $user_name,
                'rs_topik' => $rs_topik,
            ];
            // view
            return view('tim_capstone.penetapan-kelompok.index', $data);
        } else {
            return redirect('/admin/penetapan-kelompok');
        }
    }
}
