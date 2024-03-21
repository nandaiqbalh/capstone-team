<?php

namespace App\Http\Controllers\TimCapstone\JadwalSidangProposal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\JadwalSidangProposal\JadwalSidangProposalModel;
use Illuminate\Support\Facades\Hash;


class JadwalSidangProposalController extends BaseController
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
        $rs_sidang = JadwalSidangProposalModel::getDataWithPagination();
        $rs_siklus = JadwalSidangProposalModel::getSiklus();
        $rs_kelompok = JadwalSidangProposalModel::getSiklus();
        // data
        $data = [
            'rs_sidang' => $rs_sidang,
            'rs_siklus' => $rs_siklus,
            'rs_kelompok' => $rs_kelompok
        ];
        // dd($data);
        // view
        return view('tim_capstone.jadwal-pendaftaran.sidang-proposal.index', $data);
    }

    public function addJadwalSidangProposal()
    {
        $rs_siklus = JadwalSidangProposalModel::getSiklus();
        $rs_kelompok = JadwalSidangProposalModel::getKelompok();
        $rs_dosen = JadwalSidangProposalModel::getDosen();

        $data = [
            'rs_siklus' => $rs_siklus,
            'rs_kelompok' => $rs_kelompok,
            'rs_dosen' => $rs_dosen
        ];

        // view
        return view('tim_capstone.jadwal-pendaftaran.sidang-proposal.add', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addJadwalSidangProposalProcess(Request $request)
    {

        // params
        // dd($request);

        $params = [
            'siklus_id' => $request->siklus_id,
            'id_kelompok' => $request->id_kelompok,
            'tanggal_mulai' => $request->tanggal_mulai,
            'waktu' => $request->waktu,
            'ruangan_id' => $request->ruangan,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insert = JadwalSidangProposalModel::insertJadwalSidangProposal($params);
        if ($insert) {
            // $paramsDos1 = [
            //     'id_kelompok' => $request->id_kelompok,
            //     'id_dosen' => $request->id_dosen_1,
            //     'status_dosen' => 'penguji 1',
            //     'status_persetujuan' => 'menunggu persetujuan',
            // ];
            // $paramsDos2 = [
            //     'id_kelompok' => $request->id_kelompok,
            //     'id_dosen' => $request->id_dosen_2,
            //     'status_dosen' => 'penguji 2',
            //     'status_persetujuan' => 'menunggu persetujuan',
            // ];

            // // process
            // JadwalSidangProposalModel::insertDosenKelompok($paramsDos1);
            // JadwalSidangProposalModel::insertDosenKelompok($paramsDos2);
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/jadwal-pendaftaran/sidang-proposal/add')->withInput();
        }
    }

    public function editJadwalSidangProposal($id)
    {
        $jadwalSidang = JadwalSidangProposalModel::getjadwalSidang($id);
        $jadwalSidang = JadwalSidangProposalModel::getjadwalSidang2($id, $jadwalSidang->id_kelompok);

        $rs_siklus = JadwalSidangProposalModel::getSiklus();
        $rs_kelompok = JadwalSidangProposalModel::getKelompok();
        $rs_dosen = JadwalSidangProposalModel::getDosen();
        $getPenguji1 = JadwalSidangProposalModel::getDosenPenguji1($jadwalSidang->id_kelompok);
        $getPenguji2 = JadwalSidangProposalModel::getDosenPenguji2($jadwalSidang->id_kelompok);
        $data = [
            'jadwalSidang' => $jadwalSidang,
            'dosen_penguji_1' => $getPenguji1,
            'dosen_penguji_2' => $getPenguji2,
            'rs_siklus' => $rs_siklus,
            'rs_kelompok' => $rs_kelompok,
            'rs_dosen' => $rs_dosen
        ];

        // view
        return view('tim_capstone.jadwal-pendaftaran.sidang-proposal.edit', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editJadwalSidangProposalProcess(Request $request)
    {
        $params = [
            'siklus_id' => $request->siklus_id,
            'id_kelompok' => $request->id_kelompok,
            'tanggal_mulai' => $request->tanggal_mulai,
            'waktu' => $request->waktu,
            'ruangan' => $request->ruangan,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insert = JadwalSidangProposalModel::updateJadwalSidangProposal($request->id, $params);
        if ($insert) {
            // $paramsDos1 = [
            //     'id_kelompok' => $request->id_kelompok,
            //     'id_dosen' => $request->id_dosen_1,
            //     'status_dosen' => 'penguji 1',
            //     'status_persetujuan' => 'menunggu persetujuan',
            // ];
            // $paramsDos2 = [
            //     'id_kelompok' => $request->id_kelompok,
            //     'id_dosen' => $request->id_dosen_2,
            //     'status_dosen' => 'penguji 2',
            //     'status_persetujuan' => 'menunggu persetujuan',
            // ];

            // // process
            // JadwalSidangProposalModel::updateDosenKelompok($request->id_dosen1, $paramsDos1);
            // JadwalSidangProposalModel::updateDosenKelompok($request->id_dosen2, $paramsDos2);
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
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
    public function deleteJadwalSidangProposalProcess($id)
    {
        // get data
        $delete = JadwalSidangProposalModel::getDataById($id);

        // if exist
        if (!empty($delete)) {
            // process
            if (JadwalSidangProposalModel::deleteJadwalSidangProposal($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
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
            $rs_sidang = JadwalSidangProposalModel::getDataSearch($user_name);
            // dd($rs_sidang);
            // data
            $data = ['rs_sidang' => $rs_sidang, 'nama' => $user_name];
            // view
            return view('tim_capstone.pendaftaran.index', $data);
        } else {
            return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
        }
    }
}
