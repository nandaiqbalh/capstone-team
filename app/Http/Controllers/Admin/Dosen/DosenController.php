<?php

namespace App\Http\Controllers\Admin\Dosen;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\Settings\ContohHalamanModel as CHModel;

// test 
class DosenController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        CHModel::authorize('R');

        // get data with pagination
        $rs_ch = CHModel::getDataWithPagination();
        // data
        $data = ['rs_ch' => $rs_ch];
        // view
        return view('admin.settings.contoh-halaman.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        // authorize
        CHModel::authorize('C');

        // view
        return view('admin.settings.contoh-halaman.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProcess(Request $request)
    {
        // authorize
        CHModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'nama' => 'required',
            'jenis_kelamin' => 'required'
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (CHModel::insert($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/contoh-halaman');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/settings/contoh-halaman/add')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // authorize
        CHModel::authorize('R');

        // get data with pagination
        $ch = CHModel::getDataById($id);

        // check
        if (empty($ch)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/contoh-halaman');
        }

        // data
        $data = ['ch' => $ch];

        // view
        return view('admin.settings.contoh-halaman.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // authorize
        CHModel::authorize('U');

        // get data 
        $ch = CHModel::getDataById($id);

        // check
        if (empty($ch)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/settings/contoh-halaman');
        }

        // data
        $data = ['ch' => $ch];

        // view
        return view('admin.settings.contoh-halaman.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProcess(Request $request)
    {
        // authorize
        CHModel::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'id' => 'required',
            'nama' => 'required',
            'jenis_kelamin' => 'required'
        ];
        $this->validate($request, $rules);

        // params
        $params = [
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (CHModel::update($request->id, $params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/settings/contoh-halaman');
        } else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/settings/contoh-halaman/edit/' . $request->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProcess($id)
    {
        // authorize
        CHModel::authorize('D');

        // get data
        $ch = CHModel::getDataById($id);

        // if exist
        if (!empty($ch)) {
            // process
            if (CHModel::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/settings/contoh-halaman');
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
    public function search(Request $request)
    {
        // authorize
        CHModel::authorize('R');

        // data request
        $nama = $request->nama;

        // new search or reset
        if ($request->action == 'search') {
            // get data with pagination
            $rs_ch = CHModel::getDataSearch($nama);
            // data
            $data = ['rs_ch' => $rs_ch, 'nama' => $nama];
            // view
            return view('admin.settings.contoh-halaman.index', $data);
        } else {
            return redirect('/admin/settings/contoh-halaman');
        }
    }
}
