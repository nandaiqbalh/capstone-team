<?php

namespace App\Http\Controllers\Admin\Checker\QRCode;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Admin\Checker\QRCode\QRCodeModel;
use File;
use ZipArchive;

class QRCodeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        QRCodeModel::authorize('R');

        // get data with pagination 
        // Penamaan Backend pake inggris Front pake indo
        $rs_qr_code_beta = array_unique(QRCodeModel::getDataAllSubArea()->toArray(), SORT_REGULAR);

        // unset($rs_qr_code[0]);
        $rs_qr_code = [];
        foreach ($rs_qr_code_beta as $key => $value) {
            $data=[
                'sub_area'  => $value->nama_sub_area,
                'area'  => $value->nama_area,
                'sub_area_id'  => $value->sub_area_id,
                'qrcodeGenerate' => QrCode::size(400)->generate(base64_encode($value->sub_area_id))
            ];
            array_push($rs_qr_code,$data);
        }

        // dd($rs_qr_code);
        // data
        $data = $this->paginate($rs_qr_code);
        $data = [
            'rs_qr_code' => $data,
            // 'qrcodeGenerate' => $qrcodeGenerate
        ];
        
        // view
        
        // dd($data);
        return view('admin.checker.qr-code.index', $data );
    }

    // QR Code
    public function downloadQRCode(Request $request, $id)
    {
        
        $imageQr  = '/qr-code'.'/'.Str::slug($request->sub_area).'.png';
        QrCode::size(400)->margin(1)->errorCorrection('H')->format('png')
                    ->generate(base64_encode($id),public_path($imageQr));


        return response()->download(public_path($imageQr))->deleteFileAfterSend();
    }

    // QR Code
    public function downloadQRCodeAll()
    {

        $rs_qr_code_beta = array_unique(QRCodeModel::getDataAllSubArea()->toArray(), SORT_REGULAR);
        // unset($rs_qr_code[0]);
        $rs_qr_code = [];

        foreach ($rs_qr_code_beta as $key => $value) {
            $qr_name = $value->sub_area_id.'.png';
            QrCode::size(400)->margin(1)->errorCorrection('H')->format('png')
                ->generate(base64_encode($value->sub_area_id),public_path('/qr-code'.'/'.$qr_name));
            array_push($rs_qr_code,$qr_name);
        }
        $zip = new ZipArchive;
   
        $fileName = 'QRCodeSubArea.zip';
   
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
        {
            $qrCode = File::files(public_path('/qr-code'));
   
            foreach ($qrCode as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $sub_area_name = QRCodeModel::getDataSubAreaName(str_replace('.png','',$relativeNameInZipFile));
                // dd($sub_area_name);
                $zip->addFile($value, $sub_area_name.'.png');
            }
             
            $zip->close();
        }

        foreach ($rs_qr_code as $key => $value) {
            unlink(public_path('/qr-code'.'/'.$value));
        }
        
        return response()->download(public_path($fileName))->deleteFileAfterSend();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        // dd($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path'=> URL::current()]);
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
        QRCodeModel::authorize('R');

        // data request
        $search = $request->search;
        
        // new search or reset
        if($request->action == 'search') {

            // data
            $rs_qr_code_beta = array_unique(QRCodeModel::getDataSearch($search)->toArray(), SORT_REGULAR);
            // unset($rs_qr_code[0]);
            // dd($rs_qr_code_beta);
            $rs_qr_code = [];
            foreach ($rs_qr_code_beta as $key => $value) {
                $data=[
                    'sub_area'  => $value->nama_sub_area,
                    'area'  => $value->nama_area,
                    'sub_area_id'  => $value->sub_area_id,
                    'qrcodeGenerate' => QrCode::size(400)->generate(base64_encode($value->sub_area_id))
                ];
                array_push($rs_qr_code,$data);
            }

            // dd($rs_qr_code);
            // data
            $data = $this->paginate($rs_qr_code)->withQueryString();
            // dd($data);
            $data = [
                'rs_qr_code' => $data,
                'search' => $search
                // 'qrcodeGenerate' => $qrcodeGenerate
            ];
            
            // view
            
            // dd($data);
            return view('admin.checker.qr-code.index', $data );
        }
        else {
            return redirect('admin/checker/qr-code/index');
        }
    }
}
