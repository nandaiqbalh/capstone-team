<?php

namespace App\Http\Controllers\TimCapstone\RuangSidang;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\TimCapstone\BaseController;
use Illuminate\Http\Request;
use App\Models\TimCapstone\RuangSidang\RuangSidang;

class RuangSidangController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // get data with pagination
        $rs_ruangsidang = RuangSidang::getDataWithPagination();
        // data
        $data = ['rs_ruangsidang' => $rs_ruangsidang];
        // view
        return view ('tim_capstone.ruangsidang.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

       // view
        return view ('tim_capstone.ruangsidang.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       // Validate & auto redirect when fail
       $rules = [
           'nama_ruang' => 'required',
           'kode_ruang' => 'required | unique:ruang_sidangs,kode_ruang',
       ];
       $this->validate($request, $rules);


       // params
       $user_id =RuangSidang::makeMicrotimeID();
       $params = [
           'nama_ruang' => $request->nama_ruang,
           'kode_ruang' => $request->kode_ruang,
           'created_by'   => Auth::user()->user_id,
           'created_date'  => date('Y-m-d H:i:s')
       ];

       // process
       $insert_ruangsidang =RuangSidang::insertruangsidang($params);
       if ($insert_ruangsidang) {

           // flash message
           session()->flash('success', 'Data berhasil disimpan.');
           return redirect('/admin/ruangan');
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

       // get data
       $ruangan =RuangSidang::getDataById($id);

       // check
       if (empty($ruangan)) {
           // flash message
           session()->flash('danger', 'Data tidak ditemukan.');
           return redirect('/admin/ruangan');
       }

       // data
       $data = ['ruangan' => $ruangan];

       // view
       return view ('tim_capstone.ruangsidang.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

       // Validate & auto redirect when fail
       $rules = [
        'nama_ruang' => 'required',
        'kode_ruang' => 'required | unique:ruang_sidangs,kode_ruang',
    ];
    $this->validate($request, $rules);

        // params
        $user_id =RuangSidang::makeMicrotimeID();
        $params = [
            'nama_ruang' => $request->nama_ruang,
            'kode_ruang' => $request->kode_ruang,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

       // process
       if (RuangSidang::update($request->id, $params)) {
           // flash message
           session()->flash('success', 'Data berhasil disimpan.');
           return redirect('/admin/ruangan');
       } else {
           // flash message
           session()->flash('danger', 'Data gagal disimpan.');
           return redirect('/admin/ruangan' . $request->id);
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        // get data
        $ruangan =RuangSidang::getDataById($id);

        // if exist
        if (!empty($ruangan)) {
            // process
            if (RuangSidang::delete($id)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/ruangan');
            } else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/ruangan');
            }
        } else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/ruangan');
        }
    }
}
