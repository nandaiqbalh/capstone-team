<?php

namespace App\Http\Controllers\Admin\Validator\Master;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Master\LocationModel;

class LocationController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        LocationModel::authorize('R');

        // get data with pagination
        $rs_location = LocationModel::getAllPaginate();

        // data
        $data = ['rs_location' => $rs_location];

        // view
        return view('admin.validator.master.location.index', $data );
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
        LocationModel::authorize('R');

        // data request
        $query_all = $request->query_all;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_location = LocationModel::getAllSearch($query_all);
            // data
            $data = ['rs_location' => $rs_location, 'query_all'=>$query_all];
            // view
            return view('admin.validator.master.location.index', $data );
        }
        else {
            return redirect('/admin/validator/master/lokasi');
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
        LocationModel::authorize('C');

        //view
        return view('admin.validator.master.location.add');
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
        LocationModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'name' => 'required'
        ];
        $this->validate($request, $rules );
        
        $location = LocationModel::getByName($request->name);
        if(!empty($location)) {
             // flash message
             session()->flash('danger', 'Data gagal disimpan. Lokasi '.$request->name.' sudah terdaftar!');
             return redirect('/admin/validator/master/lokasi/add')->withInput();
        }

        // params
        $params =[
            'name' => $request->name,
            'description' => $request->description,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (LocationModel::insert($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/validator/master/lokasi/add');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/validator/master/lokasi/add')->withInput();
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
        LocationModel::authorize('U');

        // get data
        $location = LocationModel::getById($id);
    
        // if exist
        if(!empty($location)) {
            $data = [
                'location'=>$location
            ];
            //view
            return view('admin.validator.master.location.edit', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/master/lokasi');
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
        LocationModel::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'id' => 'required',
            'name' => 'required'
        ];
        $this->validate($request, $rules );
        
        // params
        $params =[
            'name' => $request->name,
            'description' => $request->description,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (LocationModel::update($request->id,$params)) {
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
        LocationModel::authorize('U');

        // get data
        $location = LocationModel::getById($id);
        
        // if exist
        if(!empty($location)) {
            $data = [
                'location'=>$location,
                'rs_area'=> LocationModel::getAreaByLocation($id)
            ];
            //view
            return view('admin.validator.master.location.detail', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/master/lokasi');
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
        LocationModel::authorize('D');

        // get data
        $location = LocationModel::getById($id);

        // if exist
        if(!empty($location)) {
            $total_area = LocationModel::getTotalAreaByLocation($id);
            if(intval($total_area) > 0) {
                 // flash message
                 session()->flash('danger', 'Data gagal dihapus.Lokasi '.$location->name.' masih memiliki '.$total_area.' Area!');
                 return redirect('/admin/validator/master/lokasi');
            }

            $param = [
                'data_status'   => '0',
                'modified_by'   => Auth::user()->user_id,
                'modified_date' => date('Y-m-d H:i:s')
            ];

            // process
            if(LocationModel::update($id, $param)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/validator/master/lokasi');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/validator/master/lokasi');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('admin/validator/master/lokasi');
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
        LocationModel::authorize('C');

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
            if(strtolower($sheet_header[0]) != 'no' || strtolower($sheet_header[1]) != 'nama' || strtolower($sheet_header[2]) != 'keterangan (optional)') {
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

                // cek di db sudah ada atau belum
                if(!empty(LocationModel::getByName($value[1]))) {
                    // skip
                    continue;
                }

                $data = [
                    'id'=> '',
                    'name'=> $value[1],
                    'description'=> $value[2],
                    'created_by'   => Auth::user()->user_id,
                    'created_date'  => date('Y-m-d H:i:s')
                ];

                // insert
                if(LocationModel::insert_or_ignore($data)) {
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
