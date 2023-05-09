<?php

namespace App\Http\Controllers\Admin\Validator\Master;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Master\AreaModel;

class AreaController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        AreaModel::authorize('R');

        // get data with pagination
        $rs_area = AreaModel::getAllPaginate();

        // data
        $data = ['rs_area' => $rs_area];

        // view
        return view('admin.validator.master.area.index', $data );
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
        AreaModel::authorize('R');

        // data request
        $query_all = $request->query_all;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_area = AreaModel::getAllSearch($query_all);
            // data
            $data = ['rs_area' => $rs_area, 'query_all'=>$query_all];
            // view
            return view('admin.validator.master.area.index', $data );
        }
        else {
            return redirect('/admin/validator/master/area');
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
        AreaModel::authorize('C');

        $data = [
            'rs_location'=> AreaModel::getMasterLocation(),
            'rs_round'=> AreaModel::getMasterRound()
        ];

        //view
        return view('admin.validator.master.area.add', $data);
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
        AreaModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'location_id'=>'required',
            'name' => 'required'
        ];
        $this->validate($request, $rules );

        $area = AreaModel::getByLocationAndName($request->location_id,$request->name);
        if(!empty($area)) {
             // flash message
             session()->flash('danger', 'Data gagal disimpan. Area '.$request->name.' di lokasi '.$area->location_name.' sudah terdaftar!');
             return redirect('/admin/validator/master/area/add')->withInput();
        }
        
        // params
        $params =[
            'location_id' => $request->location_id,
            'name' => $request->name,
            'description' => $request->description,
            'round_id' => $request->round_id,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (AreaModel::insert($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/validator/master/area/add');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/validator/master/area/add')->withInput();
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
        AreaModel::authorize('U');

        // get data
        $area = AreaModel::getById($id);
    
        // if exist
        if(!empty($area)) {
            $data = [
                'area'=>$area,
                'rs_location'=> AreaModel::getMasterLocation(),
                'rs_round'=> AreaModel::getMasterRound()
            ];
            //view
            return view('admin.validator.master.area.edit', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/master/area');
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
        AreaModel::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'id' => 'required',
            'location_id'=>'required',
            'name' => 'required'
        ];
        $this->validate($request, $rules );
        
        // params
        $params =[
            'location_id' => $request->location_id,
            'name' => $request->name,
            'description' => $request->description,
            'round_id' => $request->round_id,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (AreaModel::update($request->id,$params)) {
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
        AreaModel::authorize('U');
        
        // get data
        $area = AreaModel::getById($id);
    
        // if exist
        if(!empty($area)) {
            $data = [
                'area'=>$area,
                'rs_sub_area'=> AreaModel::getSubAreaByArea($id)
            ];
            //view
            return view('admin.validator.master.area.detail', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/master/area');
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
        AreaModel::authorize('D');

        // get data
        $area = AreaModel::getById($id);
        
        // if exist
        if(!empty($area)) {
            $total_area = AreaModel::getTotalSubAreaByArea($id);
            if(intval($total_area) > 0) {
                 // flash message
                 session()->flash('danger', 'Data gagal dihapus.Lokasi '.$area->name.' masih memiliki '.$total_area.'Sub Area!');
                 return redirect('/admin/validator/master/area');
            }

            $param = [
                'data_status'   => '0',
                'modified_by'   => Auth::user()->user_id,
                'modified_date' => date('Y-m-d H:i:s')
            ];

            // process
            if(AreaModel::update($id, $param)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/validator/master/area');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/validator/master/area');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('admin/validator/master/area');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importProcess(Request $request)
    {
        // authorize
        AreaModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'excel_file' => 'required|mimes:xlsx'
        ];
        $this->validate($request, $rules );
        
        // cek file
        if($request->hasFile('excel_file')) {
            // set reader excel .xlsx
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            // load file yang diupload
            $spreadsheet = $reader->load($request->file('excel_file'));
            // baca sheet aktif
            // array
            $sheet_data = $spreadsheet->getActiveSheet()->toArray();

            // -----------------------------------------------------------
            // validasi
            // ambil header
            $sheet_header = $sheet_data[0];
            
            // cek header sesuai format excel masing-masing impor data
            if(strtolower($sheet_header[0]) != 'no' || strtolower($sheet_header[1]) != 'nama lokasi' || strtolower($sheet_header[2]) != 'nama area' || strtolower($sheet_header[3]) != 'ronde' || strtolower($sheet_header[4]) != 'keterangan (optional)') {
                // response
                $response = [
                    "status"=> false,
                    "message"=> 'Format tidak sesuai!'
                ];
                return response()->json($response)->setStatusCode(200);
            }

            // looping
            // data berhasil insert
            $data_true = [];
            // data gagal insert
            $data_false = [];
            foreach ($sheet_data as $key => $value) {
                // skip header
                if($key == 0) {
                    continue;
                }

                // cek jika null maka skip
                if(empty($value[1])) {
                    // skip
                    continue;
                }

                // cek lokasi
                $lokasi = AreaModel::getLocationByName($value[1]);
                if(empty($lokasi)) {
                    // skip
                    continue;
                }

                // cek di db sudah ada atau belum
                if(!empty(AreaModel::getByLocationAndName($lokasi->id, $value[2]))) {
                    // skip
                    continue;
                }

                $data = [
                    'id'=> '',
                    'location_id'=> $lokasi->id,
                    'name'=> $value[2],
                    'round_id'=> $value[3],
                    'description'=> $value[4],
                    'created_by'   => Auth::user()->user_id,
                    'created_date'  => date('Y-m-d H:i:s')
                ];

                // insert
                if(AreaModel::insert_or_ignore($data)) {
                    array_push($data_true, $data);
                }
                else {
                    array_push($data_false, $data);
                }
            }

            // cek params
            if(count($data_true) > 0) {
                // response
                $response = [
                    "status"=> true,
                    "message"=> 'Data berhasil diimpor.'
                ];
                return response()->json($response)->setStatusCode(200);
            }
            else {
                // response
                $response = [
                    "status"=> false,
                    "message"=> 'Data sudah tersimpan.'
                ];
                return response()->json($response)->setStatusCode(200);
            }

        }
        else {
            // response
            $response = [
                "status"=> false,
                "message"=> 'Silahkan upload file excel!'
            ];
            return response()->json($response)->setStatusCode(200);
        }
    }
}
