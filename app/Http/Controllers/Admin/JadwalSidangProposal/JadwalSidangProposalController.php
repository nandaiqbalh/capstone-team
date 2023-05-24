<?php

namespace App\Http\Controllers\Admin\JadwalSidangProposal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\JadwalSidangProposal\JadwalSidangProposalModel;
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
        // authorize
        JadwalSidangProposalModel::authorize('R');

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
        return view('admin.jadwal-pendaftaran.sidang-proposal.index', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addJadwalSidangProposalProcess(Request $request)
    {

        // authorize
        JadwalSidangProposalModel::authorize('C');
        // params
        // default passwordnya mahasiswa123

        $params = [
            'siklus_id' => $request->siklus_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'ruangan' => $request->ruangan,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];
        // dd($params);

        // process
        $insert = JadwalSidangProposalModel::insertJadwalSidangProposal($params);
        if ($insert) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/jadwal-pendaftaran/sidang-proposal/add')->withInput();
        }
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
        // authorize
        JadwalSidangProposalModel::authorize('U');

        // params
        $params = [
            'siklus_id' => $request->siklus_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'ruangan' => $request->ruangan,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (JadwalSidangProposalModel::updateJadwalSidangProposal($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/jadwal-pendaftaran/sidang-proposal/edit/' . $request->user_id);
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
        // authorize
        JadwalSidangProposalModel::authorize('D');

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
        // authorize
        JadwalSidangProposalModel::authorize('R');
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
            return view('admin.pendaftaran.index', $data);
        } else {
            return redirect('/admin/jadwal-pendaftaran/sidang-proposal');
        }
    }
}
