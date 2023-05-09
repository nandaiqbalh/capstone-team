<?php

namespace App\Http\Controllers\Admin\Manajer\Master;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Manajer\Master\AsetModel;

class AsetController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        AsetModel::authorize('R');
        // get data with pagination
        $rs_items = AsetModel::getAllPaginate();

        // data
        $data = ['rs_items' => $rs_items];

        // view
        return view('admin.manajer.master.aset.index', $data );
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
        AsetModel::authorize('R');

        // data request
        $query_all = $request->query_all;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_items = AsetModel::getAllSearch($query_all);
            // data
            $data = ['rs_items' => $rs_items, 'query_all'=>$query_all];
            // view
            return view('admin.manajer.master.aset.index', $data );
        }
        else {
            return redirect('/admin/manajer/master/aset');
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
        AsetModel::authorize('C');

        //view
        return view('admin.manajer.master.aset.add');
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
        AsetModel::authorize('C');

        // Validate & auto redirect when fail
        $rules = [
            'name' => 'required'
        ];
        $this->validate($request, $rules );

        $item = AsetModel::getByName($request->name);
        if(!empty($item)) {
             // flash message
             session()->flash('danger', 'Data gagal disimpan. Item '.$request->name.' sudah terdaftar!');
             return redirect('/admin/manajer/master/aset/add')->withInput();
        }
        
        // params
        $params =[
            'name' => $request->name,
            'description' => $request->description,
            'created_by'   => Auth::user()->user_id,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (AsetModel::insert($params)) {
            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/manajer/master/aset/add');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/manajer/master/aset/add')->withInput();
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
        AsetModel::authorize('U');

        // get data
        $item = AsetModel::getById($id);
    
        // if exist
        if(!empty($item)) {
            $data = [
                'item'=>$item
            ];
            //view
            return view('admin.manajer.master.aset.edit', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/manajer/master/aset');
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
        AsetModel::authorize('U');

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
        if (AsetModel::update($request->id,$params)) {
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
        AsetModel::authorize('U');

        // get data
        $item = AsetModel::getById($id);
        
        // if exist
        if(!empty($item)) {
            $data = [
                'item'=>$item,
                'rs_item_component'=> AsetModel::getItemComponentByItems($id)
            ];
            //view
            return view('admin.manajer.master.aset.detail', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/manajer/master/aset');
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
        AsetModel::authorize('D');

        // get data
        $item = AsetModel::getById($id);

        // if exist
        if(!empty($item)) {
            $total_item_component = AsetModel::getTotalItemComponentByItems($id);
            if(intval($total_item_component) > 0) {
                 // flash message
                 session()->flash('danger', 'Data gagal dihapus. Item '.$item->name.' masih memiliki '.$total_item_component.' komponen penilaian!');
                 return redirect('/admin/manajer/master/aset');
            }

            $param = [
                'data_status'   => '0',
                'modified_by'   => Auth::user()->user_id,
                'modified_date' => date('Y-m-d H:i:s')
            ];

            // process
            if(AsetModel::update($id, $param)) {
                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/manajer/master/aset');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/manajer/master/aset');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('admin/manajer/master/aset');
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
        AsetModel::authorize('C');

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
                if(!empty(AsetModel::getByName($value[1]))) {
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
                if(AsetModel::insert_or_ignore($data)) {
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
