<?php

namespace App\Http\Controllers\TimCapstone\Topik;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\TimCapstone\Topik\TopikModel;
use Illuminate\Support\Facades\Hash;


class TopikController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        // get data with pagination
        $rs_topik = TopikModel::getDataWithPagination();
        // data
        $data = ['rs_topik' => $rs_topik];
        // view
        return view('tim_capstone.topik.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addTopik()
    {
        // view
        return view('tim_capstone.topik.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addTopikProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
        ];
        $this->validate($request, $rules);


        // params
        $user_id =TopikModel::makeMicrotimeID();
        $params = [
            'nama' => $request->nama,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insert_topik =TopikModel::inserttopik($params);
        if ($insert_topik) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/tim-capstone/topik');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/tim-capstone/topik/add')->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editTopik($id)
    {
        // get data
        $topik =TopikModel::getDataById($id);

        // check
        if (empty($topik)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/tim-capstone/topik');
        }

        // data
        $data = ['topik' => $topik];

        // view
        return view('tim_capstone.topik.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editTopikProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'nama' => $request->nama,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (TopikModel::update($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/tim-capstone/topik');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/tim-capstone/topik' . $request->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteTopikProcess($id)
    {
        // get data
        $topik =TopikModel::getDataById($id);

        // if exist
        if (!empty($topik)) {
            // process
            if (TopikModel::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/tim-capstone/topik');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/tim-capstone/topik');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/tim-capstone/topik');
        }
    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

}
