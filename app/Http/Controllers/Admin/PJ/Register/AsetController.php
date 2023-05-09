<?php

namespace App\Http\Controllers\Admin\PJ\Register;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\PJ\Register\AsetModel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;



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
        // Penamaan Backend pake inggris Front pake indo
        $rs_item = AsetModel::getDataWithPagination();
        // data
        $data = ['rs_item' => $rs_item];
        // view
        return view('admin.pj.register.item-penilaian.index', $data );
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
        $rs_location = AsetModel::getDataLocation();
        $rs_item = AsetModel::getDataItem();

        $data = [
            'rs_location' => $rs_location,
            'rs_item' => $rs_item,
        ];
        // view
        return view('admin.pj.register.item-penilaian.add',$data);
    }

    // Ajax Menampilkan Area pada add item pj
    public function ajaxAddItemArea($id)
    {
        // authorize
        AsetModel::authorize('R');

        $rs_area = AsetModel::getDataArea($id);
        // $rs_sub_area = AsetModel::getDataSubArea();
        // $rs_item = AsetModel::getDataItem();

        // response
        $response = [
            "status"=> true,
            "message"=> 'OK.',
            "data"=> [
                'rs_area'=> $rs_area

            ]
        ];
        
        // Json
        return response()->json($response);
    }

    // Ajax Menampilkan SubArea pada add item pj
    public function ajaxAddItemSubArea($id)
    {
        // authorize
        AsetModel::authorize('R');

        // $rs_area = AsetModel::getDataArea($id);
        $rs_sub_area = AsetModel::getDataSubArea($id);
        // $rs_item = AsetModel::getDataItem();

        // response
        $response = [
            "status"=> true,
            "message"=> 'OK.',
            "data"=> [
                'rs_sub_area' => $rs_sub_area,
            //  'rs_item' => $rs_item,
            ]
        ];
        
        // Json
        return response()->json($response);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function ajaxAddItemProcess(Request $request)
    {
        // authorize
        AsetModel::authorize('C');
        // dd($request);

        $status = false;

        if ($request->qty > 50) {
            $response = [
                "status"=> false,
                "message"=> 'Jumlah terlalu banyak, pastikan',
            ];
            // Json
            return response()->json($response);
        }
        for ($i=0; $i < $request->qty; $i++) { 
            $unique_id = count(AsetModel::getItemUniqueID($request->sub_area_id,$request->item_id));
            $data = [
                'branch_id' => Auth::user()->branch_id,
                'items_id' => $request->item_id,
                'unique_id' => $unique_id + 1,
                'sub_area_id' => $request->sub_area_id,
                'zona' => $request->zona,
                'data_status' => '1',
                'created_by'   => Auth::user()->user_id,
                'created_date'  => date('Y-m-d H:i:s')
            ];
            
            AsetModel::insert($data);
            // dd($data);
            // if(AsetModel::insert($data)) {
            // if($data) {
                // $round = AsetModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
                // $items_id = AsetModel::getLastItems()->id;
                // $rs_komponen_item = AsetModel::getKomponen(Auth::user()->branch_id,$round->id_ronde, $items_id);
                // // dd($items_id,$round,$rs_komponen_item);
                // foreach ($rs_komponen_item as $key => $value1) {
                //     $params =[
                //         'branch_assessment_id' => $round->branch_assessment_id,
                //         'branch_items_id' => $value1->branch_items_id,
                //         'assessment_component_id' => $value1->assessment_component_id,
                //         'created_by'   => 'System',
                //         'created_date'  => date('Y-m-d H:i:s')
                //     ];
                //     AsetModel::insertAssessmentDetail($params);
                // }

            // }

            $status = true;
        }
        
        if ($status = true) {
           // response
            $response = [
                "status"=> $status,
                "message"=> 'Data Berhasil Ditambahkan.',
            ];
        }
        else{
            // response
            $response = [
                "status"=> $status,
                "message"=> 'Data Gagal Ditambahkan.',
            ];
        }
        
        // Json
        return response()->json($response);
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
        AsetModel::authorize('R');

        // get data with pagination
        $item = AsetModel::getDataById($id);
        $rs_component = AsetModel::getDataComponent($item->items_id);
        // dd($item);
        // check
        if(empty($item)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/pj/register/item-penilaian');
        }

        // data
        $data = [
            'item' => $item,
            'rs_component' => $rs_component
        ];

        // view
        return view('admin.pj.register.item-penilaian.detail', $data );
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
        $item = AsetModel::getDataById($id);
        $rs_sub_area_branch = array_unique(AsetModel::getDataAllSubAreaBranch()->toArray(), SORT_REGULAR);
        $rs_sub_area = array_unique(AsetModel::getDataAllSubArea()->toArray(), SORT_REGULAR);
        // check
        if(empty($item)) {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/pj/register/item-penilaian');
        }
        // dd($rs_sub_area);
        // data
        $data = [
            'item' => $item,
            'rs_sub_area' => $rs_sub_area,
            'rs_sub_area_branch' => $rs_sub_area_branch
        ];

        // view
        return view('admin.pj.register.item-penilaian.edit', $data );
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
        AsetModel::authorize('U');
        // dd($request);
        // Validate & auto redirect when fail
        $rules = [
            'sub_area_id' => 'required',
        ];
        $this->validate($request, $rules );

        // params
        $params =[
            'id' => $request->id,
            'sub_area_id'=> $request->sub_area_id,
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        if (AsetModel::update($request->id, $params)) {

            // flash message
            session()->flash('success', 'Data berhasil disimpan.');
            return redirect('/admin/pj/register/item-penilaian');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disimpan.');
            return redirect('/admin/pj/register/item-penilaian/edit/'.$request->id);
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
        AsetModel::authorize('U');

        $params =[
            'id' => $id,
            'data_status' => '0',
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];
        // get data
        $akun_rs = AsetModel::getDataById($id);

        // $round = AsetModel::getDataRoundBranch(date("d"),Auth::user()->branch_id);
        // $AssessmentDetailDelete = AsetModel::getDataAssessment($round->round_id);
        // dd($AssessmentDetailDelete);
        
        // if exist
        if(!empty($akun_rs)) {
            // process
            if(AsetModel::update($id,$params)) {

                // cek detail assessment
                $round = AsetModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
              
                // $AssessmentDetailDelete = AsetModel::getDataAssessmentNow($round->branch_assessment_id);
                AsetModel::AssessmentDetailDelete($id, $round->branch_assessment_id);
                // dd($round, $AssessmentDetailDelete);
                // foreach ($AssessmentDetailDelete as $key => $value) {
                // }
                // dd($detailDelete);

                // flash message
                session()->flash('success', 'Data berhasil dihapus.');
                return redirect('/admin/pj/register/item-penilaian');
            }
            else {
                // flash message
                session()->flash('danger', 'Data gagal dihapus.');
                return redirect('/admin/pj/register/item-penilaian');
            }
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/pj/register/item-penilaian');
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
        AsetModel::authorize('R');

        // data request
        $search = $request->search;
        $round = $request->round;
        
        // new search or reset
        if($request->action == 'search' && $search && $round == null) {
            // get data with pagination
            $rs_item = AsetModel::getDataSearch($search);
            // data
            $data = ['rs_item' => $rs_item, 'search'=>$search];
            // view
            return view('admin.pj.register.item-penilaian.index', $data );
        }
        elseif ($request->action == 'search'&& $round && $search==null) {

            // get data with pagination
            $rs_item = AsetModel::getDataRound($round);
            // data
            $data = ['rs_item' => $rs_item, 'round'=>$round];
            // view
            return view('admin.pj.register.item-penilaian.index', $data );
        }
        elseif ($request->action == 'search'&& $round && $search) {
        
            $rs_item = AsetModel::getDataSearchRound($search,$round);
            // data
            $data = ['rs_item' => $rs_item, 'round'=>$round,'search'=>$search];
            // view
            return view('admin.pj.register.item-penilaian.index', $data );
        }
        elseif($request->action == 'reset') {
          
            return redirect('/admin/pj/register/item-penilaian');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadFasilitas()
    {
        // authorize
        AsetModel::authorize('R');
  
        // get data
        $fasilitas = AsetModel::getDataItemAllRS();
        $get_nama_rs = AsetModel::getNamaRS(Auth::user()->branch_id);

        // if exist
        if(!empty($fasilitas)) {
            $save_path = '/file/fasilitas-rs/';
            $save_name = 'fasilitas-'.$get_nama_rs->name.'.pdf';
            $filename2 = Str::slug('Fasilitas Rumah Sakit '.$get_nama_rs->name).'.pdf';
            
            // data
            $data = [
                'fasilitas'=>$fasilitas,
                'nama_rs'=>$get_nama_rs->name
            ];
            PDF::loadview('admin.pj.register.item-penilaian.fasilitas', $data);

    
                // data
                $data = [
                    'fasilitas'=>$fasilitas,
                    'nama_rs'=>$get_nama_rs->name
                ];

                // cek folder
                if (!is_dir(public_path($save_path))) {
                    // buat folder jika belum ada
                    mkdir(public_path($save_path), 0755, true);
                }
                // buat dan simpan file pdf
                // return view('admin.pj.register.item-penilaian.fasilitas', $data);
                $pdf = PDF::loadview('admin.pj.register.item-penilaian.fasilitas', $data);
                $pdf->save(public_path($save_path).$save_name);

                // ambil dan download file
                return response()->download(public_path($save_path).$save_name, $filename2, ['Content-Type: application/pdf'])->deleteFileAfterSend(true);

            // }


        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/pj/register/item-penilaian');
        }
    }

}
