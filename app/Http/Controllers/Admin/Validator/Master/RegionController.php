<?php

namespace App\Http\Controllers\Admin\Validator\Master;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Master\RegionModel;

class RegionController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        RegionModel::authorize('R');

        // get data with pagination
        $rs_region = RegionModel::getAllPaginate();

        // data
        $data = ['rs_region' => $rs_region];

        // view
        return view('admin.validator.master.region.index', $data );
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
        RegionModel::authorize('R');

        // data request
        $query_all = $request->query_all;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_region = RegionModel::getAllSearch($query_all);
            // data
            $data = ['rs_region' => $rs_region, 'query_all'=>$query_all];
            // view
            return view('admin.validator.master.region.index', $data );
        }
        else {
            return redirect('/admin/validator/master/region');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        // authorize
        RegionModel::authorize('C');

        //view
        return view('admin.validator.master.region.add');
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
        RegionModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'name' => 'required',
            'direg_name' => 'required'
        ];
        $this->validate($request, $rules );

        $region = RegionModel::getByName($request->name);
        if(!empty($region)) {
             // flash message
             session()->flash('danger', 'Data gagal disimpan. '.$request->name.' sudah terdaftar!');
             return redirect('/admin/validator/master/region/add')->withInput();
        }
        
        // params
        $params =[
            'name' => $request->name,
            'direg_name' => $request->direg_name,
            'description' => $request->description,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (RegionModel::insert($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/validator/master/region/add');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/validator/master/region/add')->withInput();
        }
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
        RegionModel::authorize('U');

        // get data
        $region = RegionModel::getById($id);
    
        // if exist
        if(!empty($region)) {
            $data = [
                'region'=>$region
            ];
            //view
            return view('admin.validator.master.region.edit', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/master/region');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editProcess(Request $request)
    {
        // authorize
        RegionModel::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'id' => 'required',
            'name' => 'required',
            'direg_name' => 'required'
        ];
        $this->validate($request, $rules );
        
        // params
        $params =[
            'name' => $request->name,
            'direg_name' => $request->direg_name,
            'description' => $request->description,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (RegionModel::update($request->id,$params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect()->back();
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // authorize
        RegionModel::authorize('U');

        // get data
        $region = RegionModel::getById($id);
        
        // if exist
        if(!empty($region)) {
            $data = [
                'region'=>$region
            ];
            //view
            return view('admin.validator.master.region.detail', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/master/region');
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
        RegionModel::authorize('D');

        // get data
        $region = RegionModel::getById($id);

        // if exist
        if(!empty($region)) {

            $param = [
                'data_status'   => '0',
                'modified_by'   => Auth::user()->user_id,
                'modified_date' => date('Y-m-d H:i:s')
            ];

            // process
            if(RegionModel::update($id, $param)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/validator/master/region');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/validator/master/region');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('admin/validator/master/region');
        }
    }
}
