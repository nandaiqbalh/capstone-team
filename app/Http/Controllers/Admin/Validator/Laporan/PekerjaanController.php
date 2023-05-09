<?php

namespace App\Http\Controllers\Admin\Validator\Laporan;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Laporan\PekerjaanModel;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Helpers\DateIndonesia as dtid;

use App\Models\Admin\Validator\Laporan\RondeRSModel;

class PekerjaanController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        PekerjaanModel::authorize('R');
        $data = [
            'rs_laporan_pekerjaan'  => PekerjaanModel::getAllSearch('','','', date('m'), date('Y')),
            'rs_branch'             => PekerjaanModel::getMasterBranch(),
            'rs_region'             => PekerjaanModel::getMasterRegional(),
            'month'                 => date('m'),
            'year'                  => date('Y'),
            'rs_year'               => PekerjaanModel::getListYear()
        ];

        // view
        return view('admin.validator.laporan.pekerjaan.index', $data );
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
        PekerjaanModel::authorize('R');

        // data request
        $region_name    = $request->region_name;
        $branch_id      = $request->branch_id;
        $month          = $request->month;
        $year           = $request->year;
        $ronde_id       = $request->ronde_id;
        
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_laporan_pekerjaan = PekerjaanModel::getAllSearch($region_name, $branch_id, $ronde_id, $month, $year);
            
            // data
            $data = [
                'rs_laporan_pekerjaan' => $rs_laporan_pekerjaan, 
                'rs_branch'=> PekerjaanModel::getMasterBranch(),
                'rs_region'=> PekerjaanModel::getMasterRegional(),
                'region_name'=> $region_name,
                'branch_id'=> $branch_id,
                'month'=> $month,
                'year'=> $year,
                'rs_year'=> PekerjaanModel::getListYear(),
                'ronde_id'=> $ronde_id
            ];

            // view
            return view('admin.validator.laporan.pekerjaan.index', $data );
        }
        else {
            return redirect('/admin/validator/laporan/pekerjaan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function executiveReportDownload($id)
    {
        // authorize
        PekerjaanModel::authorize('R');

        // get data
        $laporan_pekerjaan = PekerjaanModel::getById($id);
        $year   = date('Y', strtotime($laporan_pekerjaan->created_date));
        $month  = date('m', strtotime($laporan_pekerjaan->created_date));

        // if exist
        if(!empty($laporan_pekerjaan)) {

            // cek viewed date
            if(empty($laporan_pekerjaan->validator_view_date)){
                // update view date
                if(!PekerjaanModel::updateBranchAssignment($laporan_pekerjaan->id, ['validator_view_date'=> date('Y-m-d H:i:s')])){
                    // nothing
                }

                $laporan_pekerjaan = PekerjaanModel::getById($id);
            }

            $save_path = '/file/laporan-pekerjaan/'.$year.'/'.$month.'/';
            $save_name = 'er-'.md5($id).'.pdf';
            $filename2 = Str::slug('Executive Report Hasil Pekerjaan Perbaikan dan Penggantian Program ABRT-RL '.$laporan_pekerjaan->branch_name.'-'.$laporan_pekerjaan->round_name.'-Bulan-'.dtid::get_month_year2($laporan_pekerjaan->created_date)).'.pdf';
            
            //cek apakah sudah pernah didownload
            if(file_exists(public_path($save_path).$save_name)) {
                // jika sudah pernah didonwload maka ambil filenya
                return response()->download(public_path($save_path).$save_name, $filename2, ['Content-Type: application/pdf']);
            }

            // jika belum pernah didownload
            $total_komponen = PekerjaanModel::getTotalKomponenByBranchAssignmentId($laporan_pekerjaan->id);
            if($total_komponen > 0){
                // bobot penilaian
                $total_komponen_assessment      = PekerjaanModel::getTotalKomponenByBranchAssessmentId($laporan_pekerjaan->branch_assessment_id);
                $total_pembersihan_assessment   = PekerjaanModel::getTotalKomponenScoreByBranchAssessmentId($laporan_pekerjaan->branch_assessment_id,'A');
                $total_perbaikan_assessment     = PekerjaanModel::getTotalKomponenScoreByBranchAssessmentId($laporan_pekerjaan->branch_assessment_id,'B');
                $total_penggantian_assessment   = PekerjaanModel::getTotalKomponenScoreByBranchAssessmentId($laporan_pekerjaan->branch_assessment_id,'C');
                $total_belum_dinilai_assessment = PekerjaanModel::getTotalKomponenScoreByBranchAssessmentId($laporan_pekerjaan->branch_assessment_id,NULL);

                // assigmnet
                $total_perbaikan        = PekerjaanModel::getTotalKomponenScoreByBranchAssignmentId($laporan_pekerjaan->id,'B');
                $total_penggantian      = PekerjaanModel::getTotalKomponenScoreByBranchAssignmentId($laporan_pekerjaan->id,'C');
                $total_belum_dikerjakan = PekerjaanModel::getTotalKomponenByBranchAssignmentIdAndStatus($laporan_pekerjaan->id,'Belum Dikerjakan');
                $total_selesai          = PekerjaanModel::getTotalKomponenByBranchAssignmentIdAndStatus($laporan_pekerjaan->id,'Selesai');
                
                // data
                $data = [
                    'laporan_pekerjaan'     => $laporan_pekerjaan,
                    'rs_bcd_area'           => PekerjaanModel::getBCDTiapAreaByBranchAssignmentId($laporan_pekerjaan->id),
                    'bobot_penilaian' => [
                        'persen_pembersihan'    => (($total_pembersihan_assessment*1)/$total_komponen_assessment)*100,
                        'persen_perbaikan'      => (($total_perbaikan_assessment*0.5)/$total_komponen_assessment)*100,
                        'persen_penggantian'    => (($total_penggantian_assessment*0.25)/$total_komponen_assessment)*100,
                        'persen_belum_dinilai'  => (($total_belum_dinilai_assessment*0)/$total_komponen_assessment)*100,
                        'persen_abrt_rl'        => ((($total_pembersihan_assessment*1)+($total_perbaikan_assessment*0.5)+($total_penggantian_assessment*0.25)+($total_belum_dinilai_assessment*0))/$total_komponen_assessment)*100
                    ],
                    'summary'=> [
                        'total_komponen'    => $total_komponen,
                        'total_perbaikan'   => $total_perbaikan,
                        'persen_perbaikan'  => ($total_perbaikan/$total_komponen)*100,// (total_perbaikan/total_komponen)*100
                        'total_penggantian' => $total_penggantian,
                        'persen_penggantian'=> ($total_penggantian/$total_komponen)*100,// (total_penggantian/total_komponen)*100
                        'total_belum_dikerjakan' => $total_belum_dikerjakan,
                        'persen_belum_dikerjakan'=> ($total_belum_dikerjakan/$total_komponen)*100,// (total_belum_dikerjakan/total_komponen)*100  
                        'total_selesai'     => $total_selesai,
                        'persen_selesai'    => ($total_selesai/$total_komponen)*100,// (total_selesai/total_komponen)*100  
                    ],
                    'vps_img_url'=> PekerjaanModel::getAppSupportBy('vps_img_url')
                ];

                //view
                // return view('admin.validator.laporan.pekerjaan.executive-report-view', $data);

                // cek folder
                if (!is_dir(public_path($save_path))) {
                    // buat folder jika belum ada
                    mkdir(public_path($save_path), 0755, true);
                }
                
                // buat dan simpan file pdf
                $pdf = PDF::loadview('admin.validator.laporan.pekerjaan.executive-report-pdf', $data);
                $pdf->save(public_path($save_path).$save_name);

                // ambil dan download file
                return response()->download(public_path($save_path).$save_name, $filename2, ['Content-Type: application/pdf']);
            }
            else {
                // flash message
                session()->flash('danger', 'Tidak ada pekerjaan!');
                return redirect()->back();
            }


        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect()->back();
        }
    }

     /**
     * ajax unduh lampiran
     *
     * * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxLampiranExecutiveReportDownload(Request $request)
    {
        // authorize
        PekerjaanModel::authorize('R');

        // get data
        $laporan_pekerjaan  = PekerjaanModel::getById($request->id);
        $year           = date('Y', strtotime($laporan_pekerjaan->created_date));
        $month          = date('m', strtotime($laporan_pekerjaan->created_date));
        
        // if exist
        if(!empty($laporan_pekerjaan)) {

            // opt judul
            $opt_judul = [];
            foreach ($request->opt as $key => $value) {
                if($value == "B"){
                    array_push($opt_judul, 'Perbaikan');
                }
                elseif($value == "C"){
                    array_push($opt_judul, 'Penggantian');
                }
                elseif($value == "Selesai"){
                    array_push($opt_judul, 'Selesai');
                }
                elseif($value == "Belum Dikerjakan"){
                    array_push($opt_judul, 'Belum Dikerjakan');
                }
            }

            $save_path = '/file/laporan-pekerjaan/'.$year.'/'.$month.'/';
            $save_name = 'er-'.md5($request->id).'-lampiran-'.implode(',',$opt_judul).'.pdf';
            $filename2 = Str::slug('Lampiran ('.implode(',',$opt_judul).') Executive Report Hasil Pekerjaan Perbaikan dan Penggantian Program ABRT-RL '.$laporan_pekerjaan->branch_name.'-'.$laporan_pekerjaan->round_name.'-Bulan-'.dtid::get_month_year2($laporan_pekerjaan->created_date)).'.pdf';
            
            // cek apakah sudah pernah didownload
            if(file_exists(public_path($save_path).$save_name)) {
                // jika sudah pernah didonwload maka ambil filenya
                return response()->download(public_path($save_path).$save_name, $filename2, ['Content-Type: application/pdf']);
            }
            
            // jika belum pernah didownload
            $total_komponen = PekerjaanModel::getTotalKomponenByBranchAssignmentId($laporan_pekerjaan->id);

            if($total_komponen > 0){

                // default
                $rs_perbaikan       = collect([]);
                $rs_penggantian     = collect([]);
                $rs_belum_dikerjakan   = collect([]);
                $rs_selesai         = collect([]);

                // detail komponen
                foreach ($request->opt as $key => $value) {
                    if($value == 'B') {
                        $rs_perbaikan       = PekerjaanModel::getListItemByBranchAssignmentId($laporan_pekerjaan->id,'B');
                    }
                    elseif($value == 'C') {
                        $rs_penggantian     = PekerjaanModel::getListItemByBranchAssignmentId($laporan_pekerjaan->id,'C');
                    }
                    elseif($value == 'Belum Dikerjakan') {
                        $rs_belum_dikerjakan   =  PekerjaanModel::getListItemByBranchAssignmentStatus($laporan_pekerjaan->id,'Belum Dikerjakan');
                    }
                    elseif($value == 'Selesai') {
                        $rs_selesai   =  PekerjaanModel::getListItemByBranchAssignmentStatus($laporan_pekerjaan->id,'Selesai');
                    }
                }

                // start ol
                if($request->opt[0] == "B"){
                    $ol_start = 1;
                }
                elseif($request->opt[0] == "C"){
                    $ol_start = 2;
                }
                elseif($request->opt[0] == "Belum Dikerjakan"){
                    $ol_start = 3;
                }
                elseif($request->opt[0] == "Selesai"){
                    $ol_start = 4;
                }
                
                // data
                $data = [
                    'laporan_pekerjaan'     => $laporan_pekerjaan,
                    'rs_perbaikan'      => $rs_perbaikan,
                    'rs_penggantian'    => $rs_penggantian,
                    'rs_belum_dikerjakan'  => $rs_belum_dikerjakan,
                    'rs_selesai'        => $rs_selesai,
                    'opt'               => implode(',',$request->opt),
                    'opt_judul'         => implode(',',$opt_judul),
                    'ol_start'          => $ol_start,
                    'vps_img_url'       => PekerjaanModel::getAppSupportBy('vps_img_url')
                ];

                // dd($data);

                // cek folder
                if (!is_dir(public_path($save_path))) {
                    // buat folder jika belum ada
                    mkdir(public_path($save_path), 0755, true);
                }
                
                // buat dan simpan file pdf
                $pdf = PDF::loadview('admin.validator.laporan.pekerjaan.executive-report-pdf-lampiran', $data);
                $pdf->save(public_path($save_path).$save_name);

                // ambil dan download file
                return response()->download(public_path($save_path).$save_name, $filename2, ['Content-Type: application/pdf']);
            }
            else {
                // flash message
                session()->flash('danger', 'Belum ada komponen yang dinilai!');
                return redirect('/admin/validator/laporan/pekerjaan');
            }


        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/laporan/ronde');
        }
    }

     /**
     * download rekapitulasi
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function executiveReportRekapitulasiNilai(Request $request)
    {
        // bisa semua regional / regional tertentu
        // authorize
        PekerjaanModel::authorize('C');

        $pdf_template   = 'template.laporan.rekapitulasi-pekerjaan-pdf';

        // cek regional
        if($request->m_region_name == '0'){
            // semua regional
            $rs_rumah_sakit = PekerjaanModel::getListRs($request->m_ronde_id,$request->m_month,$request->m_year);
            
            // cek ronde
            if($request->m_ronde_id != '0'){
                // ronde tertentu
                $download_name  = Str::slug('EXECUTIVE REPORT HASIL PERBAIKAN DAN PENGGANTIAN PENGAWASAN PROGRAM ABRT-RL SELURUH RUMAH SAKIT RONDE-'.$request->m_ronde_id.'-Bulan-'.PekerjaanModel::bulanIndo()[$request->m_month].'-Tahun-'.$request->m_year).'.pdf';
            }
            else {
                // semua ronde
                $download_name  = Str::slug('EXECUTIVE REPORT HASIL PERBAIKAN DAN PENGGANTIAN PENGAWASAN PROGRAM ABRT-RL SELURUH RUMAH SAKIT'.'-Bulan-'.PekerjaanModel::bulanIndo()[$request->m_month].'-Tahun-'.$request->m_year).'.pdf';
            }
        }
        else {
            // regional tertentu
            $rs_rumah_sakit = PekerjaanModel::getListRsByRegional($request->m_region_name,$request->m_ronde_id,$request->m_month,$request->m_year);
            // cek ronde
            if($request->m_ronde_id != '0'){
                // ronde teretntu
                $download_name  = Str::slug('EXECUTIVE REPORT HASIL PERBAIKAN DAN PENGGANTIAN PENGAWASAN PROGRAM ABRT-RL SELURUH RUMAH SAKIT '.$request->m_region_name.'  RONDE-'.$request->m_ronde_id.'-Bulan-'.PekerjaanModel::bulanIndo()[$request->m_month].'-Tahun-'.$request->m_year).'.pdf';
            }
            else{
                // semua ronde
                $download_name  = Str::slug('EXECUTIVE REPORT HASIL PERBAIKAN DAN PENGGANTIAN PENGAWASAN PROGRAM ABRT-RL SELURUH RUMAH SAKIT '.$request->m_region_name.'-Bulan-'.PekerjaanModel::bulanIndo()[$request->m_month].'-Tahun-'.$request->m_year).'.pdf';
            }
        }

        // cek
        if($rs_rumah_sakit->count() < 1){
            // flash message
            session()->flash('danger', 'Tidak ada data');
            return redirect()->back();
        }

        // -----------------------------------------------------------------------
        
        // arr object laporan ronde
        $arr_laporan_pekerjaan = [];
        // dirop view
        $dirop_view = 0;
        // direg name
        $direg_name = '';
        $direg_view = 0;

        // ambil laporan ronde
        foreach ($rs_rumah_sakit as $key => $value) {

            // get laporan ronde
            if($request->m_ronde_id == '0'){
                // semua ronde
                // bisa lebih dari satu ronde
                $rs_laporan_pekerjaan = PekerjaanModel::getBranchAssignmentAllRoundByParam($value->id,$request->m_month,$request->m_year);
                
                // cek
                if($rs_laporan_pekerjaan->count() > 0){

                    // tampung nilai looping
                    $local_arr_laporan_pekerjaan = [];
                    // loop rs laporan ronde
                    foreach ($rs_laporan_pekerjaan as $key => $laporan_pekerjaan) {
                        // cek viewed date
                        if(empty($laporan_pekerjaan->validator_view_date)){
                            // update view date
                            if(!PekerjaanModel::updateBranchAssignment($laporan_pekerjaan->id, ['validator_view_date'=> date('Y-m-d H:i:s')])){
                                // nothing
                            }
                        }
        
                        // cek view dirop
                        if(!empty($laporan_pekerjaan->dirop_view_date)){
                            // tambah ke variabel
                            $dirop_view = $dirop_view+1;
                        }
        
                        // direg
                        if($request->m_region_name != '0'){
                            // direg name
                            if(!empty($laporan_pekerjaan->direg_name)){
                                $direg_name = $laporan_pekerjaan->direg_name;
                            }
                            
                            // direg view
                            if(empty($laporan_pekerjaan->direg_view_date)){
                                // update view date
                                if(!PekerjaanModel::updateBranchAssignment($laporan_pekerjaan->id, ['direg_view_date'=> date('Y-m-d H:i:s')])){
                                    // nothing
                                }
                                // tambah ke variabel
                                $direg_view = $direg_view+1;
                            }
                            else {
                                // tambah ke variabel
                                $direg_view = $direg_view+1;
        
                            }
                        }
        
                        // get total komponen
                        $total_komponen = PekerjaanModel::getTotalKomponenByBranchAssignmentId($laporan_pekerjaan->id);

                        if($total_komponen > 0){
            
                            // ASSIGNMENT
                            $total_perbaikan        = PekerjaanModel::getTotalKomponenScoreByBranchAssignmentId($laporan_pekerjaan->id,'B');
                            $total_penggantian      = PekerjaanModel::getTotalKomponenScoreByBranchAssignmentId($laporan_pekerjaan->id,'C');
                            $total_belum_dikerjakan = PekerjaanModel::getTotalKomponenByBranchAssignmentIdAndStatus($laporan_pekerjaan->id,'Belum Dikerjakan');
                            $total_selesai          = PekerjaanModel::getTotalKomponenByBranchAssignmentIdAndStatus($laporan_pekerjaan->id,'Selesai');

                            
                            // data
                            $data = [
                                    'branch_name'           => $value->name,
                                    'total_komponen'        => $total_komponen,
                                    'total_perbaikan'       => $total_perbaikan,
                                    'persen_perbaikan'      => ($total_perbaikan/$total_komponen)*100,// (total_perbaikan/total_komponen)*100
                                    'total_penggantian'     => $total_penggantian,
                                    'persen_penggantian'    => ($total_penggantian/$total_komponen)*100,// (total_penggantian/total_komponen)*100
                                    'total_belum_dikerjakan'   => $total_belum_dikerjakan,
                                    'persen_belum_dikerjakan'  => ($total_belum_dikerjakan/$total_komponen)*100,// (total_belum_dikerjakan/total_komponen)*100
                                    'total_selesai'         => $total_selesai,
                                    'persen_selesai'        => ($total_selesai/$total_komponen)*100,// (total_selesai/total_komponen)*100
                            ];
                            
                            // push to array as object
                            array_push($local_arr_laporan_pekerjaan, (object) $data);
                        }
                        else {
                            // data
                            $data = [
                                    'branch_name'           => $value->name,
                                    'total_komponen'        => 0,
                                    'total_perbaikan'       => 0,
                                    'persen_perbaikan'      => 0,// (total_perbaikan/total_komponen)*100
                                    'total_penggantian'     => 0,
                                    'persen_penggantian'    => 0,// (total_penggantian/total_komponen)*100
                                    'total_belum_dikerjakan'   => 0,
                                    'persen_belum_dikerjakan'  => 0,// (total_belum_dikerjakan/total_komponen)*100
                                    'total_selesai'         => 0,
                                    'persen_selesai'        => 0,// (total_selesai/total_komponen)*100
                            ];
                            // push to array as object
                            array_push($local_arr_laporan_pekerjaan, (object) $data);
                        }
                        
                    }

                    
                    // ubah ke collection
                    $coll_local_laporan_pekerjaan = collect($local_arr_laporan_pekerjaan);
                    
                    $total_komponen         = $coll_local_laporan_pekerjaan->sum('total_komponen');

                    if($total_komponen > 0){

                        // score total
                        $total_perbaikan        = $coll_local_laporan_pekerjaan->sum('total_perbaikan');
                        $total_penggantian      = $coll_local_laporan_pekerjaan->sum('total_penggantian');
                        $total_belum_dikerjakan = $coll_local_laporan_pekerjaan->sum('total_belum_dikerjakan');
                        $total_selesai          = $coll_local_laporan_pekerjaan->sum('total_selesai');
                        
                        // data
                        $data = [
                                'branch_name'               => $value->name,
                                'total_komponen'            => $total_komponen,
                                'total_perbaikan'           => $total_perbaikan,
                                'persen_perbaikan'          => ($total_perbaikan/$total_komponen)*100,// (total_perbaikan/total_komponen)*100
                                'total_penggantian'         => $total_penggantian,
                                'persen_penggantian'        => ($total_penggantian/$total_komponen)*100,// (total_penggantian/total_komponen)*100
                                'total_belum_dikerjakan'    => $total_belum_dikerjakan,
                                'persen_belum_dikerjakan'   => ($total_belum_dikerjakan/$total_komponen)*100,// (total_belum_dikerjakan/total_komponen)*100
                                'total_selesai'             => $total_selesai,
                                'persen_selesai'            => ($total_selesai/$total_komponen)*100,// (total_selesai/total_komponen)*100
                        ];
                        
                        // push to array as object
                        array_push($arr_laporan_pekerjaan, (object) $data);
                
                    }
                    else {
                        // data
                        $data = [
                                'branch_name'               => $value->name,
                                'total_komponen'            => 0,
                                'total_perbaikan'           => 0,
                                'persen_perbaikan'          => 0,// (total_perbaikan/total_komponen)*100
                                'total_penggantian'         => 0,
                                'persen_penggantian'        => 0,// (total_penggantian/total_komponen)*100
                                'total_belum_dikerjakan'    => 0,
                                'persen_belum_dikerjakan'   => 0,// (total_belum_dikerjakan/total_komponen)*100
                                'total_selesai'             => 0,
                                'persen_selesai'            => 0,// (total_selesai/total_komponen)*100
                        ];
                        // push to array as object
                        array_push($arr_laporan_pekerjaan, (object) $data);
                    }
    
                }
                else {
                    // data
                    $data = [
                            'branch_name'           => $value->name,
                            'total_komponen'        => 0,
                            'total_perbaikan'       => 0,
                            'persen_perbaikan'      => 0,// (total_perbaikan/total_komponen)*100
                            'total_penggantian'     => 0,
                            'persen_penggantian'    => 0,// (total_penggantian/total_komponen)*100
                            'total_belum_dikerjakan'   => 0,
                            'persen_belum_dikerjakan'  => 0,// (total_belum_dikerjakan/total_komponen)*100
                            'total_selesai'         => 0,
                            'persen_selesai'        => 0, // (total_selesai/total_komponen)*100
                    ];
                    // push to array as object
                    array_push($arr_laporan_pekerjaan, (object) $data);
                }
            
            }
            // ronde tertentu
            else {
                $laporan_pekerjaan = PekerjaanModel::getBranchAssignmentByParam($value->id,$request->m_ronde_id,$request->m_month,$request->m_year);
                
                // cek
                if(!empty($laporan_pekerjaan)){
                    // cek viewed date
                    if(empty($laporan_pekerjaan->validator_view_date)){
                        // update view date
                        if(!PekerjaanModel::updateBranchAssignment($laporan_pekerjaan->id, ['validator_view_date'=> date('Y-m-d H:i:s')])){
                            // nothing
                        }
                    }
    
                    // cek view dirop
                    if(!empty($laporan_pekerjaan->dirop_view_date)){
                        // tambah ke variabel
                        $dirop_view = $dirop_view+1;
                    }
    
                    // direg
                    if($request->m_region_name != '0'){
                        // direg name
                        if(!empty($laporan_pekerjaan->direg_name)){
                            $direg_name = $laporan_pekerjaan->direg_name;
                        }
                        
                        // direg view
                        if(empty($laporan_pekerjaan->direg_view_date)){
                            // update view date
                            if(!PekerjaanModel::updateBranchAssignment($laporan_pekerjaan->id, ['direg_view_date'=> date('Y-m-d H:i:s')])){
                                // nothing
                            }
                            // tambah ke variabel
                            $direg_view = $direg_view+1;
                        }
                        else {
                            // tambah ke variabel
                            $direg_view = $direg_view+1;
    
                        }
                    }
    
                    // get total komponen
                    $total_komponen = PekerjaanModel::getTotalKomponenByBranchAssignmentId($laporan_pekerjaan->id);
            
                    if($total_komponen > 0){
        
                        // score
                        $total_perbaikan        = PekerjaanModel::getTotalKomponenScoreByBranchAssignmentId($laporan_pekerjaan->id,'B');
                        $total_penggantian      = PekerjaanModel::getTotalKomponenScoreByBranchAssignmentId($laporan_pekerjaan->id,'C');
                        $total_belum_dikerjakan = PekerjaanModel::getTotalKomponenByBranchAssignmentIdAndStatus($laporan_pekerjaan->id,'Belum Dikerjakan');
                        $total_selesai          = PekerjaanModel::getTotalKomponenByBranchAssignmentIdAndStatus($laporan_pekerjaan->id,'Selesai');
                        
                        // data
                        $data = [
                                'branch_name'           => $value->name,
                                'total_komponen'        => $total_komponen,
                                'total_perbaikan'       => $total_perbaikan,
                                'persen_perbaikan'      => ($total_perbaikan/$total_komponen)*100,// (total_perbaikan/total_komponen)*100
                                'total_penggantian'     => $total_penggantian,
                                'persen_penggantian'    => ($total_penggantian/$total_komponen)*100,// (total_penggantian/total_komponen)*100
                                'total_belum_dikerjakan'   => $total_belum_dikerjakan,
                                'persen_belum_dikerjakan'  => ($total_belum_dikerjakan/$total_komponen)*100,// (total_belum_dikerjakan/total_komponen)*100
                                'total_selesai'   => $total_selesai,
                                'persen_selesai'  => ($total_selesai/$total_komponen)*100// (total_belum_dikerjakan/total_komponen)*100  
                        ];
                        
                        // push to array as object
                        array_push($arr_laporan_pekerjaan, (object) $data);
                    }
                    else {
                        // data
                        $data = [
                                'branch_name'           => $value->name,
                                'total_komponen'        => 0,
                                'total_perbaikan'       => 0,
                                'persen_perbaikan'      => 0,// (total_perbaikan/total_komponen)*100
                                'total_penggantian'     => 0,
                                'persen_penggantian'    => 0,// (total_penggantian/total_komponen)*100
                                'total_belum_dikerjakan'   => 0,
                                'persen_belum_dikerjakan'  => 0,// (total_belum_dikerjakan/total_komponen)*100
                                'total_selesai'   => 0,
                                'persen_selesai'  => 0// (total_selesai/total_komponen)*100  
                        ];
                        // push to array as object
                        array_push($arr_laporan_pekerjaan, (object) $data);
                    }
    
                }
                else {
                    // data
                    $data = [
                            'branch_name'           => $value->name,
                            'total_komponen'        => 0,
                            'total_perbaikan'       => 0,
                            'persen_perbaikan'      => 0,// (total_perbaikan/total_komponen)*100
                            'total_penggantian'     => 0,
                            'persen_penggantian'    => 0,// (total_penggantian/total_komponen)*100
                            'total_belum_dikerjakan'   => 0,
                            'persen_belum_dikerjakan'  => 0,// (total_belum_dikerjakan/total_komponen)*100
                            'total_selesai'         => 0,
                            'persen_selesai'        => 0// (total_selesai/total_komponen)*100  
                    ];
                    // push to array as object
                    array_push($arr_laporan_pekerjaan, (object) $data);
                }
            }
            
        }
        // convert arr object laporan ronde to collection
        $coll_laporan_pekerjaan = collect($arr_laporan_pekerjaan);
        
        // -----------------------------------------------------------------------
        // obj summary
        $total_komponen = $coll_laporan_pekerjaan->sum('total_komponen');
        if($total_komponen > 0){

            $total_perbaikan       = $coll_laporan_pekerjaan->sum('total_perbaikan');
            $total_penggantian     = $coll_laporan_pekerjaan->sum('total_penggantian');
            $total_belum_dikerjakan   = $coll_laporan_pekerjaan->sum('total_belum_dikerjakan');
            $total_selesai          = $coll_laporan_pekerjaan->sum('total_selesai');

            $obj_summary = (object) [
                'total_komponen'        => $total_komponen,
                'total_perbaikan'       => $total_perbaikan,
                'persen_perbaikan'      => ($total_perbaikan/$total_komponen)*100,// (total_perbaikan/total_komponen)*100
                'total_penggantian'     => $total_penggantian,
                'persen_penggantian'    => ($total_penggantian/$total_komponen)*100,// (total_penggantian/total_komponen)*100
                'total_belum_dikerjakan'   => $total_belum_dikerjakan,
                'persen_belum_dikerjakan'  => ($total_belum_dikerjakan/$total_komponen)*100,// (total_belum_dikerjakan/total_komponen)*100
                'total_selesai'   => $total_selesai,
                'persen_selesai'  => ($total_selesai/$total_komponen)*100// (total_belum_dikerjakan/total_komponen)*100  
            ];
        }
        else {
            $obj_summary = (object) [
                'total_komponen'        => $total_komponen,
                'total_perbaikan'       => 0,
                'persen_perbaikan'      => 0,// (total_perbaikan/total_komponen)*100
                'total_penggantian'     => 0,
                'persen_penggantian'    => 0,// (total_penggantian/total_komponen)*100
                'total_belum_dikerjakan'   => 0,
                'persen_belum_dikerjakan'  => 0,// (total_belum_dikerjakan/total_komponen)*100
                'total_selesai'         => 0,
                'persen_selesai'        => 0// (total_selesai/total_komponen)*100    
            ];
        }

        // ----------------------------------------------------------------------
        // bobot penilaian
        $bobot_penilaian = $this->hitungBobotNilaiBy($request);

        // -----------------------------------------------------------------------
        // signature
        $obj_signature = (object) [
            'staf_abrtrl_name'              => PekerjaanModel::getAppSupportBy('staf_abrtrl_name'),
            'mpm_dep_jangum_name'           => PekerjaanModel::getAppSupportBy('mpm_dep_jangum_name'),
            'kepdep_jangum_name'            => PekerjaanModel::getAppSupportBy('kepdep_jangum_name'),
            'dirop_name'                    => PekerjaanModel::getAppSupportBy('dirop_name'),
            'direg_name'                    => $direg_name,
            'staf_abrtrl_viewed'            => 1,
            'mpm_dep_jangum_viewed'         => 1,
            'kepdep_jangum_viewed'          => 1,
            'dirop_viewed'                  => $dirop_view > 0 ? 1 : 0,
            'direg_viewed'                  => $direg_view > 0 ? 1 : 0
        ];

        // -----------------------------------------------------------------------
        $data = [
            'ronde'             => $request->m_ronde_id,
            'bulan'             => strtoupper(PekerjaanModel::bulanIndo()[$request->m_month]),
            'tahun'             => $request->m_year,
            'rs_laporan_pekerjaan'  => $coll_laporan_pekerjaan,
            'summary'           => $obj_summary,
            'signature'         => $obj_signature,
            'bobot_penilaian'   => $bobot_penilaian,
            'region_name'       => $request->m_region_name
        ];

        // dd($data);

        $save_path = '/file/laporan-pekerjaan/rekapitulasi/'.$request->m_year.'/';
        $save_name = 'rekapitulasi-pekerjaan-'.date('y-m-d-H-i-s').'-'.uniqid().'.pdf';

        // cek folder
        if (!is_dir(public_path($save_path))) {
            // buat folder jika belum ada
            mkdir(public_path($save_path), 0755, true);
        }

        // only view
        // return view($pdf_template, $data);
        
        // buat dan simpan file pdf
        $pdf = PDF::loadview($pdf_template, $data);
        $pdf->save(public_path($save_path).$save_name);

        // ambil dan download file
        return response()->download(public_path($save_path).$save_name, $download_name, ['Content-Type: application/pdf']);

    }

    /**
     * HITUNG BOBOT NILAI
     * * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function hitungBobotNilaiBy(Request $request){

        PekerjaanModel::authorize('R');

        // cek regional
        if($request->m_region_name == '0'){
            // semua regional
            $rs_rumah_sakit = RondeRSModel::getListRs($request->m_ronde_id,$request->m_month,$request->m_year);
        }
        else {
            // regional tertentu
            $rs_rumah_sakit = RondeRSModel::getListRsByRegional($request->m_region_name,$request->m_ronde_id,$request->m_month,$request->m_year);
        }

        // cek
        if($rs_rumah_sakit->count() < 1){
            $bobot_penilaian = (object) [
                // ((total_perbaikan*1)/total_komponen)*100
                'persen_pembersihan'    => 0,
                // ((total_perbaikan*0.5)/total_komponen)*100
                'persen_perbaikan'      => 0,
                // ((total_penggantian*0.25)/total_komponen)*100
                'persen_penggantian'    => 0,
                // ((total_belum_dinilai*0)/total_komponen)*100
                'persen_belum_dinilai'  => 0,
                'persen_abrt_rl'        => 0
            ];

            return $bobot_penilaian;
        }

        // -----------------------------------------------------------------------
        
        // arr object laporan ronde
        $arr_laporan_ronde = [];

        // ambil laporan ronde
        foreach ($rs_rumah_sakit as $key => $value) {

            // get laporan ronde
            if($request->m_ronde_id == '0'){
                // semua ronde
                // bisa lebih dari satu ronde
                $rs_laporan_ronde = RondeRSModel::getBranchAssessmentAllRoundByParam($value->id,$request->m_month,$request->m_year);
                
                // cek
                if($rs_laporan_ronde->count() > 0){

                    // tampung nilai looping
                    $local_arr_laporan_ronde = [];
                    // loop rs laporan ronde
                    foreach ($rs_laporan_ronde as $key => $laporan_ronde) {
        
                        // get total komponen
                        $total_komponen = RondeRSModel::getTotalKomponenByBranchAssessmentId($laporan_ronde->id);
                        
                        if($total_komponen > 0){
            
                            // score
                            $total_pembersihan      = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'A');
                            $total_perbaikan        = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'B');
                            $total_penggantian      = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'C');
                            $total_belum_dinilai    = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,NULL);
                            
                            // data
                            $data = [
                                    'branch_name'           => $value->name,
                                    'total_komponen'        => $total_komponen,
                                    'total_pembersihan'     => $total_pembersihan,
                                    'persen_pembersihan'    => ($total_pembersihan/$total_komponen)*100,// (total_pembersihan/total_komponen)*100
                                    'total_perbaikan'       => $total_perbaikan,
                                    'persen_perbaikan'      => ($total_perbaikan/$total_komponen)*100,// (total_perbaikan/total_komponen)*100
                                    'total_penggantian'     => $total_penggantian,
                                    'persen_penggantian'    => ($total_penggantian/$total_komponen)*100,// (total_penggantian/total_komponen)*100
                                    'total_belum_dinilai'   => $total_belum_dinilai,
                                    'persen_belum_dinilai'  => ($total_belum_dinilai/$total_komponen)*100// (total_belum_dinilai/total_komponen)*100  
                            ];
                            
                            // push to array as object
                            array_push($local_arr_laporan_ronde, (object) $data);
                        }
                        else {
                            // data
                            $data = [
                                    'branch_name'           => $value->name,
                                    'total_komponen'        => 0,
                                    'total_pembersihan'     => 0,
                                    'persen_pembersihan'    => 0,// (total_pembersihan/total_komponen)*100
                                    'total_perbaikan'       => 0,
                                    'persen_perbaikan'      => 0,// (total_perbaikan/total_komponen)*100
                                    'total_penggantian'     => 0,
                                    'persen_penggantian'    => 0,// (total_penggantian/total_komponen)*100
                                    'total_belum_dinilai'   => 0,
                                    'persen_belum_dinilai'  => 0// (total_belum_dinilai/total_komponen)*100  
                            ];
                            // push to array as object
                            array_push($local_arr_laporan_ronde, (object) $data);
                        }
                        
                    }

                    
                    // ubah ke collection
                    $coll_local_laporan_ronde = collect($local_arr_laporan_ronde);
                    
                    $total_komponen         = $coll_local_laporan_ronde->sum('total_komponen');
                    if($total_komponen > 0){

                        // score total
                        $total_pembersihan     = $coll_local_laporan_ronde->sum('total_pembersihan');
                        $total_perbaikan        = $coll_local_laporan_ronde->sum('total_perbaikan');
                        $total_penggantian      = $coll_local_laporan_ronde->sum('total_penggantian');
                        $total_belum_dinilai    = $coll_local_laporan_ronde->sum('total_belum_dinilai');
                        
                        // data
                        $data = [
                                'branch_name'           => $value->name,
                                'total_komponen'        => $total_komponen,
                                'total_pembersihan'     => $total_pembersihan,
                                'persen_pembersihan'    => ($total_pembersihan/$total_komponen)*100,// (total_pembersihan/total_komponen)*100
                                'total_perbaikan'       => $total_perbaikan,
                                'persen_perbaikan'      => ($total_perbaikan/$total_komponen)*100,// (total_perbaikan/total_komponen)*100
                                'total_penggantian'     => $total_penggantian,
                                'persen_penggantian'    => ($total_penggantian/$total_komponen)*100,// (total_penggantian/total_komponen)*100
                                'total_belum_dinilai'   => $total_belum_dinilai,
                                'persen_belum_dinilai'  => ($total_belum_dinilai/$total_komponen)*100// (total_belum_dinilai/total_komponen)*100  
                        ];
                        
                        // push to array as object
                        array_push($arr_laporan_ronde, (object) $data);
                
                    }
                    else {
                        // data
                        $data = [
                                'branch_name'           => $value->name,
                                'total_komponen'        => 0,
                                'total_pembersihan'     => 0,
                                'persen_pembersihan'    => 0,// (total_pembersihan/total_komponen)*100
                                'total_perbaikan'       => 0,
                                'persen_perbaikan'      => 0,// (total_perbaikan/total_komponen)*100
                                'total_penggantian'     => 0,
                                'persen_penggantian'    => 0,// (total_penggantian/total_komponen)*100
                                'total_belum_dinilai'   => 0,
                                'persen_belum_dinilai'  => 0// (total_belum_dinilai/total_komponen)*100  
                        ];
                        // push to array as object
                        array_push($arr_laporan_ronde, (object) $data);
                    }
    
                }
                else {
                    // data
                    $data = [
                            'branch_name'           => $value->name,
                            'total_komponen'        => 0,
                            'total_pembersihan'     => 0,
                            'persen_pembersihan'    => 0,// (total_pembersihan/total_komponen)*100
                            'total_perbaikan'       => 0,
                            'persen_perbaikan'      => 0,// (total_perbaikan/total_komponen)*100
                            'total_penggantian'     => 0,
                            'persen_penggantian'    => 0,// (total_penggantian/total_komponen)*100
                            'total_belum_dinilai'   => 0,
                            'persen_belum_dinilai'  => 0// (total_belum_dinilai/total_komponen)*100  
                    ];
                    // push to array as object
                    array_push($arr_laporan_ronde, (object) $data);
                }
            
            }
            else {
                // ronde tertentu
                $laporan_ronde = RondeRSModel::getBranchAssessmentByParam($value->id,$request->m_ronde_id,$request->m_month,$request->m_year);
                
                // cek
                if(!empty($laporan_ronde)){
    
                    // get total komponen
                    $total_komponen = RondeRSModel::getTotalKomponenByBranchAssessmentId($laporan_ronde->id);
            
                    if($total_komponen > 0){
        
                        // score
                        $total_pembersihan      = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'A');
                        $total_perbaikan        = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'B');
                        $total_penggantian      = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'C');
                        $total_belum_dinilai    = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,NULL);
                        
                        // data
                        $data = [
                                'branch_name'           => $value->name,
                                'total_komponen'        => $total_komponen,
                                'total_pembersihan'     => $total_pembersihan,
                                'persen_pembersihan'    => ($total_pembersihan/$total_komponen)*100,// (total_pembersihan/total_komponen)*100
                                'total_perbaikan'       => $total_perbaikan,
                                'persen_perbaikan'      => ($total_perbaikan/$total_komponen)*100,// (total_perbaikan/total_komponen)*100
                                'total_penggantian'     => $total_penggantian,
                                'persen_penggantian'    => ($total_penggantian/$total_komponen)*100,// (total_penggantian/total_komponen)*100
                                'total_belum_dinilai'   => $total_belum_dinilai,
                                'persen_belum_dinilai'  => ($total_belum_dinilai/$total_komponen)*100// (total_belum_dinilai/total_komponen)*100  
                        ];
                        
                        // push to array as object
                        array_push($arr_laporan_ronde, (object) $data);
                    }
                    else {
                        // data
                        $data = [
                                'branch_name'           => $value->name,
                                'total_komponen'        => 0,
                                'total_pembersihan'     => 0,
                                'persen_pembersihan'    => 0,// (total_pembersihan/total_komponen)*100
                                'total_perbaikan'       => 0,
                                'persen_perbaikan'      => 0,// (total_perbaikan/total_komponen)*100
                                'total_penggantian'     => 0,
                                'persen_penggantian'    => 0,// (total_penggantian/total_komponen)*100
                                'total_belum_dinilai'   => 0,
                                'persen_belum_dinilai'  => 0// (total_belum_dinilai/total_komponen)*100  
                        ];
                        // push to array as object
                        array_push($arr_laporan_ronde, (object) $data);
                    }
    
                }
                else {
                    // data
                    $data = [
                            'branch_name'           => $value->name,
                            'total_komponen'        => 0,
                            'total_pembersihan'     => 0,
                            'persen_pembersihan'    => 0,// (total_pembersihan/total_komponen)*100
                            'total_perbaikan'       => 0,
                            'persen_perbaikan'      => 0,// (total_perbaikan/total_komponen)*100
                            'total_penggantian'     => 0,
                            'persen_penggantian'    => 0,// (total_penggantian/total_komponen)*100
                            'total_belum_dinilai'   => 0,
                            'persen_belum_dinilai'  => 0// (total_belum_dinilai/total_komponen)*100  
                    ];
                    // push to array as object
                    array_push($arr_laporan_ronde, (object) $data);
                }
            }
            
        }
        // convert arr object laporan ronde to collection
        $coll_laporan_ronde = collect($arr_laporan_ronde);
        
        // -----------------------------------------------------------------------
        // obj summary
        $total_komponen = $coll_laporan_ronde->sum('total_komponen');
        if($total_komponen > 0){
            
            $total_pembersihan     = $coll_laporan_ronde->sum('total_pembersihan');
            $total_perbaikan       = $coll_laporan_ronde->sum('total_perbaikan');
            $total_penggantian     = $coll_laporan_ronde->sum('total_penggantian');
            $total_belum_dinilai   = $coll_laporan_ronde->sum('total_belum_dinilai');

            $bobot_penilaian = (object) [
                // ((total_perbaikan*1)/total_komponen)*100
                'persen_pembersihan'    => (($total_pembersihan*1)/$total_komponen)*100,
                // ((total_perbaikan*0.5)/total_komponen)*100
                'persen_perbaikan'      => (($total_perbaikan*0.5)/$total_komponen)*100,
                // ((total_penggantian*0.25)/total_komponen)*100
                'persen_penggantian'    => (($total_penggantian*0.25)/$total_komponen)*100,
                // ((total_belum_dinilai*0)/total_komponen)*100
                'persen_belum_dinilai'  => (($total_belum_dinilai*0)/$total_komponen)*100,
                'persen_abrt_rl'        => ((($total_pembersihan*1)+($total_perbaikan*0.5)+($total_penggantian*0.25)+($total_belum_dinilai*0))/$total_komponen)*100
            ];
        }
        else {

            $bobot_penilaian = (object) [
                // ((total_perbaikan*1)/total_komponen)*100
                'persen_pembersihan'    => 0,
                // ((total_perbaikan*0.5)/total_komponen)*100
                'persen_perbaikan'      => 0,
                // ((total_penggantian*0.25)/total_komponen)*100
                'persen_penggantian'    => 0,
                // ((total_belum_dinilai*0)/total_komponen)*100
                'persen_belum_dinilai'  => 0,
                'persen_abrt_rl'        => 0
            ];
        }

        // return
        return $bobot_penilaian;
    }
}
