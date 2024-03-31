<?php

namespace App\Http\Controllers\TimCapstone\Peminatan;

use App\Http\Controllers\TimCapstone\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TimCapstone\Peminatan\PeminatanModel;
use Illuminate\Support\Facades\Hash;

class PeminatanController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get data with pagination
        $rs_peminatan = PeminatanModel::getDataWithPagination();
        // data
        $data = ['rs_peminatan' => $rs_peminatan];
        // view
        return view('tim_capstone.peminatan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addPeminatan()
    {
        // view
        return view('tim_capstone.peminatan.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addPeminatanProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'nama_peminatan' => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $user_id =PeminatanModel::makeMicrotimeID();
        $params = [
            'nama_peminatan' => $request->nama_peminatan,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        $insert_peminatan =PeminatanModel::insertpeminatan($params);
        if ($insert_peminatan) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/peminatan');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/settings/contoh-halaman/add')->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPeminatan($id)
    {
        // get data
        $peminatan =PeminatanModel::getDataById($id);

        // check
        if (empty($peminatan)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/peminatan');
        }

        // data
        $data = ['peminatan' => $peminatan];

        // view
        return view('tim_capstone.peminatan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPeminatanProcess(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'nama_peminatan' => 'required',
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'nama_peminatan' => $request->nama_peminatan,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (PeminatanModel::update($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/peminatan');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/peminatan' . $request->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePeminatanProcess($id)
    {
        // get data
        $peminatan =PeminatanModel::getDataById($id);

        // if exist
        if (!empty($peminatan)) {
            // process
            if (PeminatanModel::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/peminatan');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/peminatan');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/peminatan');
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
        $nama_peminatan = $request->nama_peminatan;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_ch =PeminatanModel::getDataSearch($nama_peminatan);
            // data
            $data = ['rs_ch' => $rs_ch, 'nama_peminatan' => $nama_peminatan];
            // view
            return view('tim_capstone.settings.contoh-halaman.index', $data);
        } else {
            return redirect('/admin/settings/contoh-halaman');
        }
    }
}
