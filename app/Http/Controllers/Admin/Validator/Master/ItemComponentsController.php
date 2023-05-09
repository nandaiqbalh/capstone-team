<?php

namespace App\Http\Controllers\Admin\Validator\Master;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Master\ItemComponentsModel;

class ItemComponentsController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        ItemComponentsModel::authorize('R');

        // get data with pagination
        $rs_item_components = ItemComponentsModel::getAllPaginate();

        // data
        $data = ['rs_item_components' => $rs_item_components];

        // view
        return view('admin.validator.master.komponen-penilaian.index', $data );
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
        ItemComponentsModel::authorize('R');

        // data request
        $query_all = $request->query_all;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_item_components = ItemComponentsModel::getAllSearch($query_all);
            // data
            $data = ['rs_item_components' => $rs_item_components, 'query_all'=>$query_all];
            // view
            return view('admin.validator.master.komponen-penilaian.index', $data );
        }
        else {
            return redirect('/admin/validator/master/komponen-penilaian');
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
        ItemComponentsModel::authorize('C');

        $data = [
            'rs_items'=> ItemComponentsModel::getMasterItems()
        ];

        //view
        return view('admin.validator.master.komponen-penilaian.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxAddProcess(Request $request)
    {
        // authorize
        ItemComponentsModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'items_id'=>'required',
            'name' => 'required',
            'parameter_true' => 'required'
        ];
        $this->validate($request, $rules );

        $item_component = ItemComponentsModel::getByItemsIdAndName($request->items_id, $request->name);
        if(!empty($item_component)) {
             // response
            $response = [
                "status"=> false,
                "message"=> 'Komponen penilaian '.$request->name.' sudah terdaftar!'
            ];
            return response()->json($response)->setStatusCode(200);
        }
        
        // params
        $params =[
            'items_id' => $request->items_id,
            'name' => $request->name,
            'parameter_true' => $request->parameter_true,
            'parameter_false' => 'Tidak '.$request->parameter_true,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (ItemComponentsModel::insert($params)) {
            // response
            $response = [
                "status"=> true,
                "message"=> 'Komponen penilaian berhasil disimpan.'
            ];
            return response()->json($response)->setStatusCode(200);
        }
        else {
            // response
            $response = [
                "status"=> false,
                "message"=> 'Komponen penilaian gagal disimpan.'
            ];
            return response()->json($response)->setStatusCode(200);
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
        ItemComponentsModel::authorize('U');

        // get data
        $item_components = ItemComponentsModel::getById($id);
    
        // if exist
        if(!empty($item_components)) {
            $data = [
                'item_components'=>$item_components
            ];
            //view
            return view('admin.validator.master.komponen-penilaian.edit', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/master/komponen-penilaian');
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
        ItemComponentsModel::authorize('U');

        // Validate & auto redirect when fail
        $rules = [
            'id' => 'required',
            'name' => 'required',
            'parameter_true' => 'required'
        ];
        $this->validate($request, $rules );
        
        // params
        $params =[
            'name' => $request->name,
            'parameter_true' => $request->parameter_true,
            'parameter_false' => 'Tidak '.$request->parameter_true,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (ItemComponentsModel::update($request->id,$params)) {
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
        ItemComponentsModel::authorize('U');
        
        // get data
        $item_components = ItemComponentsModel::getById($id);
    
        // if exist
        if(!empty($item_components)) {
            $data = [
                'item_components'=>$item_components
            ];
            //view
            return view('admin.validator.master.komponen-penilaian.detail', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/master/komponen-penilaian');
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
        ItemComponentsModel::authorize('D');

        // get data
        $item_components = ItemComponentsModel::getById($id);
        
        // if exist
        if(!empty($item_components)) {

            $param = [
                'data_status'   => '0',
                'modified_by'   => Auth::user()->user_id,
                'modified_date' => date('Y-m-d H:i:s')
            ];

            // process
            if(ItemComponentsModel::update($id, $param)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/validator/master/komponen-penilaian');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/validator/master/komponen-penilaian');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('admin/validator/master/komponen-penilaian');
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
        ItemComponentsModel::authorize('C');

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
            if(strtolower($sheet_header[0]) != 'no' || strtolower($sheet_header[1]) != 'nama item' || strtolower($sheet_header[2]) != 'nama komponen' || strtolower($sheet_header[3]) != 'parameter' || strtolower($sheet_header[4]) != 'keterangan (optional)') {
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

                // cek 
                $item = ItemComponentsModel::getItemByName($value[1]);
                if(empty($item)) {
                    // skip
                    continue;
                }

                // cek di db sudah ada atau belum
                if(!empty(ItemComponentsModel::getByItemsIdAndName($item->id, $value[2]))) {
                    // skip
                    continue;
                }

                $data = [
                    'id'=> '',
                    'items_id'=> $item->id,
                    'name'=> $value[2],
                    'parameter_true'=> $value[3],
                    'parameter_false'=> 'Tidak '.$value[3],
                    'created_by'   => Auth::user()->user_id,
                    'created_date'  => date('Y-m-d H:i:s')
                ];

                // insert
                if(ItemComponentsModel::insert_or_ignore($data)) {
                    array_push($data_true, $data );
                }
                else {
                    array_push($data_false, $data );
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
