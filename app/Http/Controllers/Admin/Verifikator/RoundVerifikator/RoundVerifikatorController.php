<?php

namespace App\Http\Controllers\Admin\Verifikator\RoundVerifikator;

use Illuminate\Support\Facades\Http;
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
use App\Models\Admin\Verifikator\RoundVerifikator\RoundVerifikatorModel;
use File;
use phpDocumentor\Reflection\Types\Null_;
use ZipArchive;

class RoundVerifikatorController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(phpinfo());
        // authorize
        RoundVerifikatorModel::authorize('R');
        // get data with pagination 
        // Penamaan Backend pake inggris Front pake indo
        // $round = RoundVerifikatorModel::getDataRoundBranch(date("d"),Auth::user()->branch_id);
        $round = RoundVerifikatorModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
        // dd($round->id_ronde);
        $rs_assessment = RoundVerifikatorModel::getDataAssessment($round->branch_assessment_id,$round->id_ronde,$round->id_ronde);
        // dd($round);
        $rs_assessment_count = RoundVerifikatorModel::getDataAssessmentCount($round->branch_assessment_id,$round->id_ronde);
        $rs_assessment_revision = RoundVerifikatorModel::getDataAssessmentRevision($round->branch_assessment_id,$round->id_ronde);
        $rs_assessment_all = RoundVerifikatorModel::getDataAssessmentAll($round->branch_assessment_id,$round->id_ronde);

        $revisi = 'Ya';
        foreach ($rs_assessment as $key => $value) {
            if ($rs_assessment[$key]->status_revisi == 'Proses') {
                $revisi = 'Ya';
                break;
            }
            else{
                $revisi = 'Tidak';
            }
        }
        $data = [
            'round' => $round,
            'rs_assessment' => $rs_assessment,
            'rs_assessment_revision' => $rs_assessment_revision,
            'revisi' => $revisi,
            'api_img' =>env('API_IMG'),
            'rs_assessment_count' => $rs_assessment_count,
            'rs_assessment_all' => $rs_assessment_all
            // 'lengkap' => $lengkap
        ];
        // dd($data);
        // view
        return view('admin.verifikator.ronde.index', $data );
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelAllProcess()
    {
        $round = RoundVerifikatorModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
        $rs_assessment_revision = RoundVerifikatorModel::getDataAssessmentRevision($round->branch_assessment_id,$round->id_ronde);
        foreach ($rs_assessment_revision as $key => $value) {
            $params =[
                'id' => $rs_assessment_revision[$key]->assessment_id,
                'revision_description' => NULL,
                'revision_status' => 'Tidak',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
            RoundVerifikatorModel::updateRevision($rs_assessment_revision[$key]->assessment_id, $params);
        }
        session()->flash('success', 'Revisi Berhasil Dibatalkan!.');
        return redirect('/admin/verifikator/ronde/penilaian/');
        // dd($rs_assessment_revision);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function revisionProcess(Request $request)
    {
        // authorize
        RoundVerifikatorModel::authorize('U');

        if ($request->batal_revisi) {
            // params
            $params =[
                'id' => $request->branch_assessment_detail_id,
                'revision_description' => NULL,
                'revision_status' => 'Tidak',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
        }
        else{

            if ($request->branch_assessment_detail_revision == null) {
                $response = [
                    "status"=> false,
                    "message"=> 'Gagal.',
                ];
                // Json
                return response()->json($response);
            }
            // params
            $params =[
                'id' => $request->branch_assessment_detail_id,
                'revision_description' => $request->branch_assessment_detail_revision,
                'revision_status' => 'Draft',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
        }
        // process
        if (RoundVerifikatorModel::updateRevision($request->branch_assessment_detail_id, $params)) {

            // response
            $response = [
                "status"=> true,
                "message"=> 'OK.',
                "data"=> [
                    'params'=> $params
                ]
            ];
            // Json
            return response()->json($response);
        }
        else {
            // response
            $response = [
                "status"=> false,
                "message"=> 'Gagal.',
            ];
            // Json
            return response()->json($response);
        }
    }

    //Mail Revision
    public function revisionMailProcess()
    {
                
        $getRevisionDraft = RoundVerifikatorModel::getRevisionDraft();
        // dd($getRevisionDraft);
        
        foreach ($getRevisionDraft as $key => $value) {
            $params =[
                'id' => $value->id,
                'revision_status' => 'Proses',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
            RoundVerifikatorModel::updateRevision($value->id,$params);
            
        }
        //Email
        $round      = RoundVerifikatorModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
        $getMailChecker = RoundVerifikatorModel::emailChecker($round->branch_id);
        $branch_name = RoundVerifikatorModel::getMasterBranchByID($round->branch_id)->name;
        // params mail
        $data = [
            'title' => 'Revisi '.$round->nama_ronde,
            'user_name' => $getMailChecker->user_name,
            'user_email' => $getMailChecker->user_email,
            'nama_ronde' => $round->nama_ronde,
            'nama_rs' => $branch_name,
            'url'=> env('APP_URL').'/admin/checker/ronde/penilaian',
            'email_type'=> 'revision-round'
        ];
        // send mail
        if(parent::sendMail($data)) {
            // flash message
            session()->flash('success', 'Revisi berhasil dikirim.');
            return redirect('/admin/verifikator/ronde/penilaian');
        }
        
        else {
            // flash message
            session()->flash('danger', 'Revisi gagal dikirim.');
            return redirect('/admin/verifikator/ronde/penilaian')->withInput();
        }
    }

    //Mail Revision 2
    public function revisionMailProcess2()
    {
        $getRevisionDraft = RoundVerifikatorModel::getRevisionDraft();
        // dd($getRevisionDraft);
        
        foreach ($getRevisionDraft as $key => $value) {
            $params =[
                'id' => $value->id,
                'revision_status' => 'Proses',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
            RoundVerifikatorModel::updateRevision($value->id,$params);
            
        }
        //Email
        $round = RoundVerifikatorModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
        $getMailChecker = RoundVerifikatorModel::emailChecker($round->branch_id);
        $branch_name = RoundVerifikatorModel::getMasterBranchByID($round->branch_id)->name;
        // params mail
        $data = [
            'title' => 'Revisi '.$round->nama_ronde,
            'user_name' => $getMailChecker->user_name,
            'user_email' => $getMailChecker->user_email,
            'nama_ronde' => $round->nama_ronde,
            'nama_rs' => $branch_name,
            'url'=> env('APP_URL').'/admin/checker/ronde/penilaian',
            'email_type'=> 'revision-round'
        ];

        parent::sendMail($data);
        //Email V1
        $getMailV1 = RoundVerifikatorModel::emailV1($round->branch_id);
        // $branch_name = RoundVerifikatorModel::getMasterBranchByID($round->branch_id)->name;
        // params mail
        $data = [
            'title' => 'Revisi '.$round->nama_ronde,
            'user_name' => $getMailV1->user_name,
            'user_email' => $getMailV1->user_email,
            'nama_ronde' => $round->nama_ronde,
            'nama_rs' => $branch_name,
            'url'=> env('APP_URL').'/admin/verifikator/ronde/penilaian',
            'email_type'=> 'revision-round'
        ];
        // send mail
        if(parent::sendMail($data)) {
            // flash message
            session()->flash('success', 'Revisi berhasil dikirim.');
            return redirect('/admin/verifikator/ronde/penilaian');
        }
        
        else {
            // flash message
            session()->flash('danger', 'Revisi gagal dikirim.');
            return redirect('/admin/verifikator/ronde/penilaian')->withInput();
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve1Process(Request $request)
    {
        // authorize
        RoundVerifikatorModel::authorize('U');

        // params
        $params =[
            'verifikator_1_name'=>Auth::user()->user_name,
            'verifikator_1_approved_date'=> date('Y-m-d'),
            'status' => 'persetujuan verifikator 2',
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];
        // process
        RoundVerifikatorModel::update($request->branch_assessment_id, $params);
        //Email
        // $round = RoundVerifikatorModel::getDataRoundBranch(date("d"),Auth::user()->branch_id);
        $round = RoundVerifikatorModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
        $getMailV2 = RoundVerifikatorModel::emailV2($round->branch_id);
        // dd($getMailV2);
        // params mail
        $data = [
            'title' => 'Persetujuan '.$round->nama_ronde,
            'user_name' => $getMailV2->user_name,
            'user_email' => $getMailV2->user_email,
            'nama_ronde' => $round->nama_ronde,
            'nama_rs' => $round->nama_rs,
            'url'=> env('APP_URL').'/admin/verifikator/ronde/penilaian',
            'email_type'=> 'approve-round'
        ];
        // send mail
        if(parent::sendMail($data)) {
            // flash message
            session()->flash('success', 'Data berhasil disetujui.');
            return redirect('/admin/verifikator/ronde/penilaian/');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disetujui.');
            return redirect('/admin/verifikator/ronde/penilaian/');
        }
    }
 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve2Process(Request $request)
    {
        // authorize
        RoundVerifikatorModel::authorize('U');

        // params
        $params =[
            'verifikator_2_name'=>Auth::user()->user_name,
            'verifikator_2_approved_date'=> date('Y-m-d'),
            'status' => 'selesai',
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        RoundVerifikatorModel::update($request->branch_assessment_id, $params);
        //Email
        $round = RoundVerifikatorModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
        $getMailValidator = RoundVerifikatorModel::emailValidator($round->branch_id);
        $branch_name = RoundVerifikatorModel::getMasterBranchByID($round->branch_id)->name;
        // params mail
        $data = [
            'title' => 'Persetujuan '.$round->nama_ronde.' '.$branch_name,
            'user_name' => $getMailValidator->user_name,
            'user_email' => $getMailValidator->user_email,
            'nama_ronde' => $round->nama_ronde,
            'nama_rs' => $branch_name,
            'url'=> env('APP_URL').'/admin/verifikator/ronde/penilaian',
            'email_type'=> 'approve2-round'
        ];
        // send mail
        if(parent::sendMail($data)) {
            // flash message
            session()->flash('success', 'Data berhasil disetujui.');
            return redirect('/admin/verifikator/ronde/penilaian/');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disetujui.');
            return redirect('/admin/verifikator/ronde/penilaian/');
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
        RoundVerifikatorModel::authorize('R');

        // data request
        $search = $request->search;
        $round = RoundVerifikatorModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
        
    
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_assessment = RoundVerifikatorModel::getDataSearch($search, $round->id_ronde);
            $rs_assessment_revision = RoundVerifikatorModel::getDataAssessmentRevision($round->branch_assessment_id,$round->id_ronde);
            $rs_assessment_count = RoundVerifikatorModel::getDataAssessmentCount($round->branch_assessment_id,$round->id_ronde);
            $rs_assessment_all = RoundVerifikatorModel::getDataAssessmentAll($round->branch_assessment_id,$round->id_ronde);
            // dd($rs_assessment);
            // data
            // if ($rs_assessment_count == $rs_assessment_all) {
            //     # code...
            //     $lengkap = 'ya';
            // }
            // else{
            //     $lengkap = 'tidak';
            // }
            // data
            $revisi = 'Ya';
            foreach ($rs_assessment as $key => $value) {
                if ($rs_assessment[$key]->status_revisi == 'Ya') {
                    $revisi = 'Ya';
                    break;
                }
                else{
                    $revisi = 'Tidak';
                }
            }
            $data = [
                'rs_assessment' => $rs_assessment, 
                'rs_assessment_revision' => $rs_assessment_revision,
                'search'=>$search,
                'round'=>$round,
                'revisi'=>$revisi,
                'api_img' =>env('API_IMG'),
                'rs_assessment_count' => $rs_assessment_count,
                'rs_assessment_all' => $rs_assessment_all
                // 'lengkap' => $lengkap
            ];
            // view
            return view('admin.verifikator.ronde.index', $data );
        }
        else {
            return redirect('/admin/verifikator/ronde/penilaian/');
        }
    }
}
