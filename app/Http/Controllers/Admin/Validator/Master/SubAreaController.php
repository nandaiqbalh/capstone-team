<?php

namespace App\Http\Controllers\Admin\Validator\Master;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Master\SubAreaModel;

class SubAreaController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        SubAreaModel::authorize('R');

        // get data with pagination
        $rs_sub_area = SubAreaModel::getAllPaginate();

        // data
        $data = ['rs_sub_area' => $rs_sub_area];

        // view
        return view('admin.validator.master.sub-area.index', $data );
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
        SubAreaModel::authorize('R');

        // data request
        $query_all = $request->query_all;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_sub_area = SubAreaModel::getAllSearch($query_all);
            // data
            $data = ['rs_sub_area' => $rs_sub_area, 'query_all'=>$query_all];
            // view
            return view('admin.validator.master.sub-area.index', $data );
        }
        else {
            return redirect('/admin/validator/master/sub-area');
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
        SubAreaModel::authorize('C');

        $data = [
            'rs_area'=> SubAreaModel::getMasterArea()
        ];

        //view
        return view('admin.validator.master.sub-area.add', $data);
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
        SubAreaModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'area_id'=>'required',
            'name' => 'required'
        ];
        $this->validate($request, $rules );
        
        $sub_area = SubAreaModel::getByAreaAndName($request->area_id,$request->name);
        if(!empty($sub_area)) {
             // flash message
             session()->flash('danger', 'Data gagal disimpan. Sub Area '.$request->name.' di area '.$sub_area->area_name.' sudah terdaftar!');
             return redirect('/admin/validator/master/sub-area/add')->withInput();
        }

        // params
        $params =[
            'area_id' => $request->area_id,
            'name' => $request->name,
            'description' => $request->description,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (SubAreaModel::insert($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/validator/master/sub-area/add');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/validator/master/sub-area/add')->withInput();
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
        SubAreaModel::authorize('U');

        // get data
        $sub_area = SubAreaModel::getById($id);
    
        // if exist
        if(!empty($sub_area)) {
            $data = [
                'sub_area'=>$sub_area,
                'rs_area'=> SubAreaModel::getMasterArea()
            ];
            //view
            return view('admin.validator.master.sub-area.edit', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/master/sub-area');
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
        SubAreaModel::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'id' => 'required',
            'area_id'=>'required',
            'name' => 'required'
        ];
        $this->validate($request, $rules );
        
        // params
        $params =[
            'area_id' => $request->area_id,
            'name' => $request->name,
            'description' => $request->description,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (SubAreaModel::update($request->id,$params)) {
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
        SubAreaModel::authorize('U');
        
        // get data
        $sub_area = SubAreaModel::getById($id);
    
        // if exist
        if(!empty($sub_area)) {
            $data = [
                'sub_area'=>$sub_area
            ];
            //view
            return view('admin.validator.master.sub-area.detail', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/master/sub-area');
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
        SubAreaModel::authorize('D');

        // get data
        $sub_area = SubAreaModel::getById($id);
        
        // if exist
        if(!empty($sub_area)) {
            
            $param = [
                'data_status'   => '0',
                'modified_by'   => Auth::user()->user_id,
                'modified_date' => date('Y-m-d H:i:s')
            ];

            // process
            if(SubAreaModel::update($id, $param)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/validator/master/sub-area');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/validator/master/sub-area');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('admin/validator/master/sub-area');
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
        SubAreaModel::authorize('C');

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
            if(strtolower($sheet_header[0]) != 'no' || strtolower($sheet_header[1]) != 'nama area' || strtolower($sheet_header[2]) != 'nama sub area' || strtolower($sheet_header[3]) != 'keterangan (optional)') {
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
                $area = SubAreaModel::getAreaByName($value[1]);
                if(empty($area)) {
                    // skip
                    continue;
                }

                // cek di db sudah ada atau belum
                if(!empty(SubAreaModel::getByAreaAndName($area->id, $value[2]))) {
                    // skip
                    continue;
                }

                $data = [
                    'id'=> '',
                    'area_id'=> $area->id,
                    'name'=> $value[2],
                    'description'=> $value[3],
                    'created_by'   => Auth::user()->user_id,
                    'created_date'  => date('Y-m-d H:i:s')
                ];

                // insert
                if(SubAreaModel::insert_or_ignore($data)) {
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
