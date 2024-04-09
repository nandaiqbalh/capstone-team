<?php

namespace App\Http\Controllers\TimCapstone\SidangTA\PeriodeSidangTA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\SidangTA\PeriodeSidangTA\PeriodeSidangTAModel;
use Illuminate\Support\Facades\Hash;

class PeriodeSidangTAController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get data with pagination
        $rs_jadwal_periode_sidang_ta = PeriodeSidangTAModel::getDataWithPagination();
        // data
        $data = ['rs_jadwal_periode_sidang_ta' => $rs_jadwal_periode_sidang_ta];
        // view
        return view('tim_capstone.sidang-ta.periode-sidang-ta.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addPeriodeSidangTA()
    {
        // view
        return view('tim_capstone.sidang-ta.periode-sidang-ta.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPeriodeSidangTAProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'nama_periode' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ];
        $this->validate($request, $rules);

        // params

        $params = [
            'nama_periode' => $request->nama_periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insert_periode_sidang_ta = PeriodeSidangTAModel::insertjadwal_periode_sidang_ta($params);
        if ($insert_periode_sidang_ta) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/periode-sidang-ta');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/siklus/periode-sidang-ta/add')->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPeriodeSidangTA($id)
    {

        // get data
        $periode_sidang_ta = PeriodeSidangTAModel::getDataById($id);

        // check
        if (empty($periode_sidang_ta)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/periode-sidang-ta');
        }

        // data
        $data = ['periode_sidang_ta' => $periode_sidang_ta];

        // view
        return view('tim_capstone.sidang-ta.periode-sidang-ta.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPeriodeSidangTAProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'nama_periode' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'nama_periode' => $request->nama_periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (PeriodeSidangTAModel::update($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/periode-sidang-ta');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/periode-sidang-ta' . $request->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePeriodeSidangTAProcess($id)
    {
        // get data
        $periode_sidang_ta =PeriodeSidangTAModel::getDataById($id);

        // if exist
        if (!empty($periode_sidang_ta)) {
            // process
            if (PeriodeSidangTAModel::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/periode-sidang-ta');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/periode-sidang-ta');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/periode-sidang-ta');
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
        // data request
        $nama_periode = $request->nama_periode;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_ch =PeriodeSidangTAMOdel::getDataSearch($nama);
            // data
            $data = ['rs_ch' => $rs_ch, 'nama_periode' => $nama_periode];
            // view
            return view('tim_capstone.settings.contoh-halaman.index', $data);
        } else {
            return redirect('/admin/settings/contoh-halaman');
        }
    }
}
