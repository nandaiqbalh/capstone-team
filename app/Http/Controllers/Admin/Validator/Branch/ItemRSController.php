<?php

namespace App\Http\Controllers\Admin\Validator\Branch;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Branch\ItemRSModel;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class ItemRSController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function itemList($id)
    {
        // authorize
        ItemRSModel::authorize('R');
        $rs_item = ItemRSModel::getDataItemPagination($id);
        $nama_rs = ItemRSModel::getRSbyId($id);
        // data
        $data = [
            'rs_item' => $rs_item,
            'nama_rs' => $nama_rs
        ];
        // view
        return view('admin.validator.branch.branch.item', $data );
    }

    
    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request,$id)
    {
        // authorize
        ItemRSModel::authorize('R');

        // data request
        $search = $request->search;
        $round = $request->round;
        $nama_rs = ItemRSModel::getRSbyId($id);
        // new search or reset
        if($request->action == 'search' && $search && $round==null) {
          
            // get data with pagination
            $rs_item = ItemRSModel::getDataSearch($id,$search);
            // data
            $data = ['rs_item' => $rs_item,'nama_rs' => $nama_rs, 'search'=>$search];
            // view
            return view('admin.validator.branch.branch.item', $data );
        }
        elseif ($request->action == 'search'&& $round && $search==null) {

            // get data with pagination
            $rs_item = ItemRSModel::getDataRound($id,$round);
            // data
            $data = ['rs_item' => $rs_item,'nama_rs' => $nama_rs, 'round'=>$round];
            // view
            return view('admin.validator.branch.branch.item', $data );
        }
        elseif ($request->action == 'search'&& $round && $search) {
        
            $rs_item = ItemRSModel::getDataSearchRound($id,$search,$round);
            // data
            $data = ['rs_item' => $rs_item,'nama_rs' => $nama_rs, 'round'=>$round,'search'=>$search];
            // view
            return view('admin.validator.branch.branch.item', $data );
        }
        elseif($request->action == 'reset') {
          
            return redirect('/admin/checker/register/item-penilaian');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadFasilitas($id)
    {
        // authorize
        ItemRSModel::authorize('R');
        // dd('a');
        // get data
        $fasilitas = ItemRSModel::getDataItemAllRS($id);
        $get_nama_rs = ItemRSModel::getNamaRS($id);

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
            PDF::loadview('admin.checker.register.item-penilaian.fasilitas', $data);

    
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
                // return view('admin.checker.register.item-penilaian.fasilitas', $data);
                $pdf = PDF::loadview('admin.checker.register.item-penilaian.fasilitas', $data);
                $pdf->save(public_path($save_path).$save_name);

                // ambil dan download file
                return response()->download(public_path($save_path).$save_name, $filename2, ['Content-Type: application/pdf'])->deleteFileAfterSend(true);

            // }


        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/checker/register/item-penilaian');
        }
    }


}
