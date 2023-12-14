<?php

namespace App\Http\Controllers\TimCapstone\Siklus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Siklus\SiklusModel;
use Illuminate\Support\Facades\Hash;


class SiklusController extends BaseController
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
        SiklusModel::authorize('R');
        // dd(SiklusModel::getData());

        // get data with pagination
        $dt_siklus = SiklusModel::getDataWithPagination();
        // data
        $data = ['dt_siklus' => $dt_siklus];
        // view
        return view('tim_capstone.siklus.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addSiklus()
    {
        // authorize
        SiklusModel::authorize('C');

        // view
        return view('tim_capstone.siklus.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addSiklusProcess(Request $request)
    {

        // authorize
        SiklusModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'tahun_ajaran' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'status' => 'required',
        ];
        $this->validate($request, $rules);


        // params
        // default passwordnya Siklus123

        $params = [
            'tahun_ajaran' => $request->tahun_ajaran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insert_siklus = SiklusModel::insertSiklus($params);
        if ($insert_siklus) {


            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/siklus');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/siklus/add')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detailSiklus($id)
    {
        // authorize
        SiklusModel::authorize('R');

        // get data with pagination
        $siklus = SiklusModel::getDataById($id);

        // check
        if (empty($siklus)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/siklus');
        }

        // data
        $data = ['siklus' => $siklus];

        // view
        return view('tim_capstone.siklus.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editSiklus($id)
    {
        // authorize
        SiklusModel::authorize('U');

        // get data
        $siklus = SiklusModel::getDataById($id);

        // check
        if (empty($siklus)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/siklus');
        }

        // data
        $data = ['siklus' => $siklus];

        // view
        return view('tim_capstone.siklus.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editSiklusProcess(Request $request)
    {
        // authorize
        SiklusModel::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'tahun_ajaran' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'status' => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'tahun_ajaran' => $request->tahun_ajaran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (SiklusModel::update($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/siklus');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/siklus/edit/' . $request->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSiklusProcess($id)
    {
        // authorize
        SiklusModel::authorize('D');

        // get data
        $siklus = SiklusModel::getDataById($id);

        // if exist
        if (!empty($siklus)) {
            // process
            if (SiklusModel::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/siklus');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/siklus');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/siklus');
        }
    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // authorize
        SiklusModel::authorize('R');

        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_ch = SiklusModel::getDataSearch($nama);
            // data
            $data = ['rs_ch' => $rs_ch, 'nama' => $nama];
            // view
            return view('tim_capstone.settings.contoh-halaman.index', $data);
        } else {
            return redirect('/admin/settings/contoh-halaman');
        }
    }
}
