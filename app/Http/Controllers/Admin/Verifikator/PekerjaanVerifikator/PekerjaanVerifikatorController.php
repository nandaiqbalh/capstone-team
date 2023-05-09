<?php

namespace App\Http\Controllers\Admin\Verifikator\PekerjaanVerifikator;

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
use App\Models\Admin\Verifikator\PekerjaanVerifikator\PekerjaanVerifikatorModel;
use File;
use phpDocumentor\Reflection\Types\Null_;
use ZipArchive;

class PekerjaanVerifikatorController extends BaseController
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
        PekerjaanVerifikatorModel::authorize('R');
        // get data with pagination 
        // Penamaan Backend pake inggris Front pake indo
        // $round = PekerjaanVerifikatorModel::getDataRoundBranch(date("d"),Auth::user()->branch_id);
        $round = PekerjaanVerifikatorModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);

        (($round->id_ronde - 1) > 0) ? ($round_assignment = $round->id_ronde - 1) : $round_assignment = 4;
        
        $getPekerjaanRound = PekerjaanVerifikatorModel::getBranchAssignment($round->branch_id,$round_assignment);
        // cek asesment kosong atau tidak 
        if (is_null($getPekerjaanRound)) {
            return view('admin.checker.pekerjaan.kosong');
        }
        $rs_assignment = PekerjaanVerifikatorModel::getDataAssignment($getPekerjaanRound->id,$getPekerjaanRound->round_id);
        // dd($rs_assignment);
        $rs_assignment_count = PekerjaanVerifikatorModel::getDataAssignmentCount($getPekerjaanRound->id,$getPekerjaanRound->round_id);
        $rs_assignment_revision = PekerjaanVerifikatorModel::getDataAssignmentRevision($getPekerjaanRound->id,$getPekerjaanRound->round_id);
        $rs_assignment_all = PekerjaanVerifikatorModel::getDataAssignmentAll($getPekerjaanRound->id,$getPekerjaanRound->round_id);

        $revisi = 'Ya';
        foreach ($rs_assignment as $key => $value) {
            if ($rs_assignment[$key]->status_revisi == 'Proses') {
                $revisi = 'Ya';
                break;
            }
            else{
                $revisi = 'Tidak';
            }
        }
        $data = [
            'round' => $getPekerjaanRound,
            'rs_assignment' => $rs_assignment,
            'vps_img' => PekerjaanVerifikatorModel::getAppSupportBy('vps_img_url'),
            'rs_assignment_revision' => $rs_assignment_revision,
            'revisi' => $revisi,
            'api_img' =>env('API_IMG'),
            'rs_assignment_count' => $rs_assignment_count,
            'rs_assignment_all' => $rs_assignment_all
            // 'lengkap' => $lengkap
        ];
        // dd($data);
        // view
        return view('admin.verifikator.pekerjaan.index', $data );
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
        $round = PekerjaanVerifikatorModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
        (($round->id_ronde - 1) > 0) ? ($round_assignment = $round->id_ronde - 1) : $round_assignment = 4;
        
        $getPekerjaanRound = PekerjaanVerifikatorModel::getBranchAssignment($round->branch_id,$round_assignment);
        $rs_assignment_revision = PekerjaanVerifikatorModel::getDataAssignmentRevision($getPekerjaanRound->id,$getPekerjaanRound->round_id);
        foreach ($rs_assignment_revision as $key => $value) {
            $params =[
                'id' => $rs_assignment_revision[$key]->assessment_id,
                'revision_description' => NULL,
                'revision_status' => 'Tidak',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
            PekerjaanVerifikatorModel::updateRevision($rs_assignment_revision[$key]->assessment_id, $params);
        }
        session()->flash('success', 'Revisi Berhasil Dibatalkan!.');
        return redirect('/admin/verifikator/ronde/pekerjaan/');
        // dd($rs_assignment_revision);
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
        PekerjaanVerifikatorModel::authorize('U');

        if ($request->batal_revisi) {
            // params
            $params =[
                'id' => $request->branch_assignment_detail_id,
                'revision_description' => NULL,
                'revision_status' => 'Tidak',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
        }
        else{

            if ($request->branch_assignment_detail_revision == null) {
                $response = [
                    "status"=> false,
                    "message"=> 'Gagal.',
                ];
                // Json
                return response()->json($response);
            }
            // params
            $params =[
                'id' => $request->branch_assignment_detail_id,
                'revision_description' => $request->branch_assignment_detail_revision,
                'revision_status' => 'Draft',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
        }
        // process
        if (PekerjaanVerifikatorModel::updateRevision($request->branch_assignment_detail_id, $params)) {

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
                
        $getRevisionDraft = PekerjaanVerifikatorModel::getRevisionDraft();
        // dd($getRevisionDraft);
        
        foreach ($getRevisionDraft as $key => $value) {
            $params =[
                'id' => $value->id,
                'revision_status' => 'Proses',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
            PekerjaanVerifikatorModel::updateRevision($value->id,$params);
            
        }
        //Email
        // $round = PekerjaanVerifikatorModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
        $round = PekerjaanVerifikatorModel::getDataRoundBranch(date('d'), date('m'), date('Y'), Auth::user()->branch_id);
        (($round->id_ronde - 1) > 0) ? ($round_assignment = $round->id_ronde - 1) : $round_assignment = 4;

        $getPekerjaanRound = PekerjaanVerifikatorModel::getBranchAssignment($round->branch_id, $round_assignment);
        $getMailChecker = PekerjaanVerifikatorModel::emailChecker($round->branch_id);
        $branch_name = PekerjaanVerifikatorModel::getMasterBranchByID($round->branch_id)->name;
        // params mail
        $data = [
            'title' => 'Revisi Ronde '. $getPekerjaanRound->round_id,
            'user_name' => $getMailChecker->user_name,
            'user_email' => $getMailChecker->user_email,
            'nama_ronde' => 'Ronde '. $getPekerjaanRound->round_id,
            'nama_rs' => $branch_name,
            'url'=> env('APP_URL').'/admin/checker/ronde/pekerjaan',
            'email_type'=> 'revision-pekerjaan'
        ];
        // send mail
        if(parent::sendMail($data)) {
            // flash message
            session()->flash('success', 'Revisi berhasil dikirim.');
            return redirect('/admin/verifikator/ronde/pekerjaan');
        }
        
        else {
            // flash message
            session()->flash('danger', 'Revisi gagal dikirim.');
            return redirect('/admin/verifikator/ronde/pekerjaan')->withInput();
        }
    }

    //Mail Revision 2
    public function revisionMailProcess2()
    {
        $getRevisionDraft = PekerjaanVerifikatorModel::getRevisionDraft();
        // dd($getRevisionDraft);
        
        foreach ($getRevisionDraft as $key => $value) {
            $params =[
                'id' => $value->id,
                'revision_status' => 'Proses',
                'modified_by'   => Auth::user()->user_id,
                'modified_date'  => date('Y-m-d H:i:s')
            ];
            PekerjaanVerifikatorModel::updateRevision($value->id,$params);
            
        }
        //Email
        $round = PekerjaanVerifikatorModel::getDataRoundBranch(date('d'), date('m'), date('Y'), Auth::user()->branch_id);
        (($round->id_ronde - 1) > 0) ? ($round_assignment = $round->id_ronde - 1) : $round_assignment = 4;

        $getPekerjaanRound = PekerjaanVerifikatorModel::getBranchAssignment($round->branch_id, $round_assignment);
        $getMailChecker = PekerjaanVerifikatorModel::emailChecker($round->branch_id);
        $branch_name = PekerjaanVerifikatorModel::getMasterBranchByID($round->branch_id)->name;
        // params mail
        $data = [
            'title' => 'Revisi Ronde ' . $getPekerjaanRound->round_id,
            'user_name' => $getMailChecker->user_name,
            'user_email' => $getMailChecker->user_email,
            'nama_ronde' => 'Ronde ' . $getPekerjaanRound->round_id,
            'nama_rs' => $branch_name,
            'url'=> env('APP_URL').'/admin/checker/ronde/pekerjaan',
            'email_type'=> 'revision-pekerjaan'
        ];

        parent::sendMail($data);
        //Email V1
        $getMailV1 = PekerjaanVerifikatorModel::emailV1($round->branch_id);
        // $branch_name = PekerjaanVerifikatorModel::getMasterBranchByID($round->branch_id)->name;
        // params mail
        $data = [
            'title' => 'Revisi Ronde ' . $getPekerjaanRound->round_id,
            'user_name' => $getMailV1->user_name,
            'user_email' => $getMailV1->user_email,
            'nama_ronde' => 'Ronde ' . $getPekerjaanRound->round_id,
            'nama_rs' => $branch_name,
            'url'=> env('APP_URL').'/admin/verifikator/ronde/pekerjaan',
            'email_type'=> 'revision-pekerjaan'
        ];
        // send mail
        if(parent::sendMail($data)) {
            // flash message
            session()->flash('success', 'Revisi berhasil dikirim.');
            return redirect('/admin/verifikator/ronde/pekerjaan');
        }
        
        else {
            // flash message
            session()->flash('danger', 'Revisi gagal dikirim.');
            return redirect('/admin/verifikator/ronde/pekerjaan')->withInput();
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
        PekerjaanVerifikatorModel::authorize('U');

        $round = PekerjaanVerifikatorModel::getDataRoundBranch(date('d'), date('m'), date('Y'), Auth::user()->branch_id);
        (($round->id_ronde - 1) > 0) ? ($round_assignment = $round->id_ronde - 1) : $round_assignment = 4;
        $getPekerjaanRound = PekerjaanVerifikatorModel::getBranchAssignment($round->branch_id, $round_assignment);
        // params
        $params =[
            'verifikator_1_name'=>Auth::user()->user_name,
            'verifikator_1_approved_date'=> date('Y-m-d'),
            'status' => 'persetujuan verifikator 2',
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];
        // process
        PekerjaanVerifikatorModel::update($getPekerjaanRound->id, $params);
        //Email
        // dd('a');

        // $round = PekerjaanVerifikatorModel::getDataRoundBranch(date("d"),Auth::user()->branch_id);
        $getMailV2 = PekerjaanVerifikatorModel::emailV2($round->branch_id);
        // dd($getMailV2);
        // params mail
        $data = [
            'title' => 'Persetujuan Pekerjaan Ronde ' . $getPekerjaanRound->round_id,
            'user_name' => $getMailV2->user_name,
            'user_email' => $getMailV2->user_email,
            'nama_ronde' => 'Ronde ' . $getPekerjaanRound->round_id,
            'nama_rs' => $round->nama_rs,
            'url'=> env('APP_URL').'/admin/verifikator/ronde/pekerjaan',
            'email_type'=> 'approve-pekerjaan'
        ];
        // send mail
        if(parent::sendMail($data)) {
            // flash message
            session()->flash('success', 'Data berhasil disetujui.');
            return redirect('/admin/verifikator/ronde/pekerjaan/');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disetujui.');
            return redirect('/admin/verifikator/ronde/pekerjaan/');
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
        PekerjaanVerifikatorModel::authorize('U');

        //Email
        $round = PekerjaanVerifikatorModel::getDataRoundBranch(date('d'), date('m'), date('Y'), Auth::user()->branch_id);
        (($round->id_ronde - 1) > 0) ? ($round_assignment = $round->id_ronde - 1) : $round_assignment = 4;
        
        $getPekerjaanRound = PekerjaanVerifikatorModel::getBranchAssignment($round->branch_id, $round_assignment);
        // params
        $params =[
            'verifikator_2_name'=>Auth::user()->user_name,
            'verifikator_2_approved_date'=> date('Y-m-d'),
            'status' => 'selesai',
            'modified_by'   => Auth::user()->user_id,
            'modified_date'  => date('Y-m-d H:i:s')
        ];

        // process
        PekerjaanVerifikatorModel::update($getPekerjaanRound->id, $params);
        $getMailValidator = PekerjaanVerifikatorModel::emailValidator($round->branch_id);
        $branch_name = PekerjaanVerifikatorModel::getMasterBranchByID($round->branch_id)->name;
        // params mail
        $data = [
            'title' => 'Persetujuan Pekerjaan Ronde ' . $getPekerjaanRound->round_id,
            'user_name' => $getMailValidator->user_name,
            'user_email' => $getMailValidator->user_email,
            'nama_ronde' => 'Ronde ' . $getPekerjaanRound->round_id,
            'nama_rs' => $branch_name,
            'url'=> env('APP_URL').'/admin/verifikator/ronde/pekerjaan',
            'email_type'=> 'approve2-pekerjaan'
        ];
        // send mail
        if(parent::sendMail($data)) {
            // flash message
            session()->flash('success', 'Data berhasil disetujui.');
            return redirect('/admin/verifikator/ronde/pekerjaan/');
        }
        else {
            // flash message
            session()->flash('danger', 'Data gagal disetujui.');
            return redirect('/admin/verifikator/ronde/pekerjaan/');
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
        PekerjaanVerifikatorModel::authorize('R');

        // data request
        $search = $request->search;
        $round = PekerjaanVerifikatorModel::getDataRoundBranch(date('d'),date('m'),date('Y'),Auth::user()->branch_id);
        (($round->id_ronde - 1) > 0) ? ($round_assignment = $round->id_ronde - 1) : $round_assignment = 4;
        
        $getPekerjaanRound = PekerjaanVerifikatorModel::getBranchAssignment($round->branch_id,$round_assignment);
    
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_assignment = PekerjaanVerifikatorModel::getDataSearch($search, $round->id_ronde);
            $rs_assignment_revision = PekerjaanVerifikatorModel::getDataAssignmentRevision($getPekerjaanRound->id,$getPekerjaanRound->round_id);
            $rs_assignment_count = PekerjaanVerifikatorModel::getDataAssignmentCount($getPekerjaanRound->id,$getPekerjaanRound->round_id);
            $rs_assignment_all = PekerjaanVerifikatorModel::getDataAssignmentAll($getPekerjaanRound->id,$getPekerjaanRound->round_id);
            // dd($rs_assignment);
            // data
            // if ($rs_assignment_count == $rs_assignment_all) {
            //     # code...
            //     $lengkap = 'ya';
            // }
            // else{
            //     $lengkap = 'tidak';
            // }
            // data
            $revisi = 'Ya';
            foreach ($rs_assignment as $key => $value) {
                if ($rs_assignment[$key]->status_revisi == 'Ya') {
                    $revisi = 'Ya';
                    break;
                }
                else{
                    $revisi = 'Tidak';
                }
            }
            $data = [
                'rs_assignment' => $rs_assignment, 
                'rs_assignment_revision' => $rs_assignment_revision,
                'search'=>$search,
                'round'=>$round,
                'revisi'=>$revisi,
                'api_img' =>env('API_IMG'),
                'rs_assignment_count' => $rs_assignment_count,
                'rs_assignment_all' => $rs_assignment_all
                // 'lengkap' => $lengkap
            ];
            // view
            return view('admin.verifikator.pekerjaan.index', $data );
        }
        else {
            return redirect('/admin/verifikator/ronde/pekerjaan/');
        }
    }
}
