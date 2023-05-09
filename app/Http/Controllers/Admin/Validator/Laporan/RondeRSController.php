<?php

namespace App\Http\Controllers\Admin\Validator\Laporan;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Laporan\RondeRSModel;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Helpers\DateIndonesia as dtid;

class RondeRSController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        RondeRSModel::authorize('R');
        $data = [
            'rs_laporan_ronde'=> RondeRSModel::getAllSearch('','','', date('m'), date('Y')),
            'rs_branch'=> RondeRSModel::getMasterBranch(),
            'rs_region'=> RondeRSModel::getMasterRegional(),
            'month'=> date('m'),
            'year'=> date('Y'),
            'rs_year'=> RondeRSModel::getListYear()
        ];

        // dd($data);

        // view
        return view('admin.validator.laporan.ronde-rs.index', $data );
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
        RondeRSModel::authorize('R');

        // data request
        $region_name    = $request->region_name;
        $branch_id      = $request->branch_id;
        $month          = $request->month;
        $year           = $request->year;
        $ronde_id       = $request->ronde_id;
        
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_laporan_ronde = RondeRSModel::getAllSearch($region_name, $branch_id, $ronde_id, $month, $year);
            
            // data
            $data = [
                'rs_laporan_ronde' => $rs_laporan_ronde, 
                'rs_branch'=> RondeRSModel::getMasterBranch(),
                'rs_region'=> RondeRSModel::getMasterRegional(),
                'region_name'=> $region_name,
                'branch_id'=> $branch_id,
                'month'=> $month,
                'year'=> $year,
                'rs_year'=> RondeRSModel::getListYear(),
                'ronde_id'=> $ronde_id
            ];

            // view
            return view('admin.validator.laporan.ronde-rs.index', $data );
        }
        else {
            return redirect('/admin/validator/laporan/ronde');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function executiveReportView($id)
    {
        // authorize
        RondeRSModel::authorize('R');

        // get data
        $laporan_ronde = RondeRSModel::getById($id);
        
        // if exist
        if(!empty($laporan_ronde)) {

            $total_komponen = RondeRSModel::getTotalKomponenByBranchAssessmentId($laporan_ronde->id);

            if($total_komponen > 0){

                // score
                $total_pembersihan  = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'A');
                $total_perbaikan    = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'B');
                $total_penggantian  = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'C');
                $total_belum_dinilai  = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,NULL);
        
                // parameter
                $total_aman         = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Aman');
                $total_bersih       = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Bersih');
                $total_rapih        = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Rapih');
                $total_tampak_baru  = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tampak Baru');
                $total_ramah_lingkungan = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Ramah Lingkungan');
                $total_tidak_aman   = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Aman');
                $total_tidak_bersih = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Bersih');
                $total_tidak_rapih  = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Rapih');
                $total_tidak_tampak_baru = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Tampak Baru');
                $total_tidak_ramah_lingkungan = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Ramah Lingkungan');

                // detail komponen
                $rs_pembersihan     = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'A');
                $rs_perbaikan       = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'B');
                $rs_penggantian     = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'C');
                $rs_belum_dinilai   = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,NULL);
                
                // data
                $data = [
                    'laporan_ronde'     => $laporan_ronde,
                    'rs_pembersihan'    => $rs_pembersihan,
                    'rs_perbaikan'      => $rs_perbaikan,
                    'rs_penggantian'    => $rs_penggantian,
                    'rs_belum_dinilai'  => $rs_belum_dinilai,
                    'rs_abc_area'       => RondeRSModel::getABCTiapAreaByBranchAssessmentId($laporan_ronde->id),
                    'bobot_penilaian' => [
                        // ((total_perbaikan*1)/total_komponen)*100
                        'persen_pembersihan'=> (($total_pembersihan*1)/$total_komponen)*100,
                        // ((total_perbaikan*0.5)/total_komponen)*100
                        'persen_perbaikan'=> (($total_perbaikan*0.5)/$total_komponen)*100,
                        // ((total_penggantian*0.25)/total_komponen)*100
                        'persen_penggantian'=> (($total_penggantian*0.25)/$total_komponen)*100,
                        // ((total_belum_dinilai*0)/total_komponen)*100
                        'persen_belum_dinilai'=> (($total_belum_dinilai*0)/$total_komponen)*100,
                        'persen_abrt_rl'=> ((($total_pembersihan*1)+($total_perbaikan*0.5)+($total_penggantian*0.25)+($total_belum_dinilai*0))/$total_komponen)*100
                    ],
                    'akumulasi_parameter'=>[
                        'persen_aman'=> ($total_aman == 0) ? 0 : ($total_aman/($total_aman+$total_tidak_aman))*100,
                        'persen_bersih'=> ($total_bersih == 0) ? 0 : ($total_bersih/($total_bersih+$total_tidak_bersih))*100,
                        'persen_rapih'=> ($total_rapih == 0) ? 0 : ($total_rapih/($total_rapih+$total_tidak_rapih))*100,
                        'persen_tampak_baru'=> ($total_tampak_baru == 0) ? 0 : ($total_tampak_baru/($total_tampak_baru+$total_tidak_tampak_baru))*100,
                        'persen_ramah_lingkungan'=> ($total_ramah_lingkungan == 0) ? 0 : ($total_ramah_lingkungan/($total_ramah_lingkungan+$total_tidak_ramah_lingkungan))*100,
                    ],
                    'summary'=> [
                        'total_komponen'    => $total_komponen,
                        'total_pembersihan' => $total_pembersihan,
                        'persen_pembersihan'=> ($total_pembersihan/$total_komponen)*100,// (total_pembersihan/total_komponen)*100
                        'total_perbaikan'   => $total_perbaikan,
                        'persen_perbaikan'  => ($total_perbaikan/$total_komponen)*100,// (total_perbaikan/total_komponen)*100
                        'total_penggantian' => $total_penggantian,
                        'persen_penggantian'=> ($total_penggantian/$total_komponen)*100,// (total_penggantian/total_komponen)*100
                        'total_belum_dinilai' => $total_belum_dinilai,
                        'persen_belum_dinilai'=> ($total_belum_dinilai/$total_komponen)*100// (total_belum_dinilai/total_komponen)*100  
                    ],
                    'vps_img_url'=> RondeRSModel::getAppSupportBy('vps_img_url')
                ];
            }
            else {
                $data = [
                    'laporan_ronde'     => $laporan_ronde,
                    'rs_pembersihan'    => collect([]),
                    'rs_perbaikan'      => collect([]),
                    'rs_penggantian'    => collect([]),
                    'rs_belum_dinilai'  => collect([]),
                    'rs_abc_area'       => RondeRSModel::getABCTiapAreaByBranchAssessmentId($laporan_ronde->id),
                    'bobot_penilaian' => [
                        // ((total_perbaikan*1)/total_komponen)*100
                        'persen_pembersihan'=> 0,
                        // ((total_perbaikan*0.5)/total_komponen)*100
                        'persen_perbaikan'=> 0,
                        // ((total_perbaikan*0.25)/total_komponen)*100
                        'persen_penggantian'=> 0,
                        // ((total_belum_dinilai*0)/total_komponen)*100
                        'persen_belum_dinilai'=> 0,
                        'persen_abrt_rl'=> 0
                    ],
                    'akumulasi_parameter'=>[
                        'persen_aman'=> 0,
                        'persen_bersih'=> 0,
                        'persen_rapih'=> 0,
                        'persen_tampak_baru'=> 0,
                        'persen_ramah_lingkungan'=> 0,
                    ],
                    'summary'=> [
                        'total_komponen'    => 0,
                        'total_pembersihan' => 0,
                        'persen_pembersihan'=> 0,// (total_pembersihan/total_komponen)*100
                        'total_perbaikan'   => 0,
                        'persen_perbaikan'  => 0,// (total_perbaikan/total_komponen)*100
                        'total_penggantian' => 0,
                        'persen_penggantian'=> 0,// (total_penggantian/total_komponen)*100
                        'total_belum_dinilai' => 0,
                        'persen_belum_dinilai'=> 0// (total_belum_dinilai/total_komponen)*100 
                    ],
                    'vps_img_url'=> RondeRSModel::getAppSupportBy('vps_img_url')
                ];
            }

            //view
            return view('admin.validator.laporan.ronde-rs.executive-report-view', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/laporan/ronde');
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
        RondeRSModel::authorize('R');

        // get data
        $laporan_ronde = RondeRSModel::getById($id);
        $year   = date('Y', strtotime($laporan_ronde->created_date));
        $month  = date('m', strtotime($laporan_ronde->created_date));
        
        // if exist
        if(!empty($laporan_ronde)) {

            // cek viewed date
            if(empty($laporan_ronde->validator_view_date)){
                // update view date
                if(!RondeRSModel::updateBranchAssessment($laporan_ronde->id, ['validator_view_date'=> date('Y-m-d H:i:s')])){
                    // nothing
                }
                $laporan_ronde = RondeRSModel::getById($id);
            }

            $save_path = '/file/laporan-ronde/'.$year.'/'.$month.'/';
            $save_name = 'er-'.md5($id).'.pdf';
            $filename2 = Str::slug('Executive Report Checklist Pengawasan Program ABRT-RL '.$laporan_ronde->branch_name.'-'.$laporan_ronde->round_name.'-Bulan-'.dtid::get_month_year2($laporan_ronde->created_date)).'.pdf';
            
            // cek apakah sudah pernah didownload
            if(file_exists(public_path($save_path).$save_name)) {
                // jika sudah pernah didonwload maka ambil filenya
                return response()->download(public_path($save_path).$save_name, $filename2, ['Content-Type: application/pdf']);
            }
            else {
                // jika belum pernah didownload

                $total_komponen = RondeRSModel::getTotalKomponenByBranchAssessmentId($laporan_ronde->id);
    
                if($total_komponen > 0){
    
                    // score
                    $total_pembersihan  = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'A');
                    $total_perbaikan    = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'B');
                    $total_penggantian  = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'C');
                    $total_belum_dinilai  = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,NULL);
            
                    // parameter
                    $total_aman         = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Aman');
                    $total_bersih       = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Bersih');
                    $total_rapih        = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Rapih');
                    $total_tampak_baru  = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tampak Baru');
                    $total_ramah_lingkungan = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Ramah Lingkungan');
                    $total_tidak_aman   = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Aman');
                    $total_tidak_bersih = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Bersih');
                    $total_tidak_rapih  = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Rapih');
                    $total_tidak_tampak_baru = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Tampak Baru');
                    $total_tidak_ramah_lingkungan = RondeRSModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Ramah Lingkungan');
                    
                    // data
                    $data = [
                        'laporan_ronde'     => $laporan_ronde,
                        'rs_abc_area'       => RondeRSModel::getABCTiapAreaByBranchAssessmentId($laporan_ronde->id),
                        'bobot_penilaian' => [
                            // ((total_perbaikan*1)/total_komponen)*100
                            'persen_pembersihan'=> (($total_pembersihan*1)/$total_komponen)*100,
                            // ((total_perbaikan*0.5)/total_komponen)*100
                            'persen_perbaikan'=> (($total_perbaikan*0.5)/$total_komponen)*100,
                            // ((total_penggantian*0.25)/total_komponen)*100
                            'persen_penggantian'=> (($total_penggantian*0.25)/$total_komponen)*100,
                            // ((total_belum_dinilai*0)/total_komponen)*100
                            'persen_belum_dinilai'=> (($total_belum_dinilai*0)/$total_komponen)*100,
                            'persen_abrt_rl'=> ((($total_pembersihan*1)+($total_perbaikan*0.5)+($total_penggantian*0.25)+($total_belum_dinilai*0))/$total_komponen)*100
                        ],
                        'akumulasi_parameter'=>[
                            'persen_aman'=> ($total_aman == 0) ? 0 : ($total_aman/($total_aman+$total_tidak_aman))*100,
                            'persen_bersih'=> ($total_bersih == 0) ? 0 : ($total_bersih/($total_bersih+$total_tidak_bersih))*100,
                            'persen_rapih'=> ($total_rapih == 0) ? 0 : ($total_rapih/($total_rapih+$total_tidak_rapih))*100,
                            'persen_tampak_baru'=> ($total_tampak_baru == 0) ? 0 : ($total_tampak_baru/($total_tampak_baru+$total_tidak_tampak_baru))*100,
                            'persen_ramah_lingkungan'=> ($total_ramah_lingkungan == 0) ? 0 : ($total_ramah_lingkungan/($total_ramah_lingkungan+$total_tidak_ramah_lingkungan))*100,
                        ],
                        'summary'=> [
                            'total_komponen'    => $total_komponen,
                            'total_pembersihan' => $total_pembersihan,
                            'persen_pembersihan'=> ($total_pembersihan/$total_komponen)*100,// (total_pembersihan/total_komponen)*100
                            'total_perbaikan'   => $total_perbaikan,
                            'persen_perbaikan'  => ($total_perbaikan/$total_komponen)*100,// (total_perbaikan/total_komponen)*100
                            'total_penggantian' => $total_penggantian,
                            'persen_penggantian'=> ($total_penggantian/$total_komponen)*100,// (total_penggantian/total_komponen)*100
                            'total_belum_dinilai' => $total_belum_dinilai,
                            'persen_belum_dinilai'=> ($total_belum_dinilai/$total_komponen)*100// (total_belum_dinilai/total_komponen)*100  
                        ],
                        'vps_img_url'=> RondeRSModel::getAppSupportBy('vps_img_url')
                    ];

                    // cek folder
                    if (!is_dir(public_path($save_path))) {
                        // buat folder jika belum ada
                        mkdir(public_path($save_path), 0755, true);
                    }
                    
                    // buat dan simpan file pdf
                    $pdf = PDF::loadview('admin.validator.laporan.ronde-rs.executive-report-pdf', $data);
                    $pdf->save(public_path($save_path).$save_name);
    
                    // ambil dan download file
                    return response()->download(public_path($save_path).$save_name, $filename2, ['Content-Type: application/pdf']);
                }
                else {
                    // flash message
                    session()->flash('danger', 'Belum ada komponen yang dinilai!');
                    return redirect()->back();
                }

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
        RondeRSModel::authorize('R');

        // get data
        $laporan_ronde = RondeRSModel::getById($request->id);
        $year = date('Y', strtotime($laporan_ronde->created_date));
        $month = date('m', strtotime($laporan_ronde->created_date));
        
        // if exist
        if(!empty($laporan_ronde)) {

            $save_path = '/file/laporan-ronde/'.$year.'/'.$month.'/';
            $save_name = 'er-'.md5($request->id).'-lampiran-'.implode('-',$request->opt).'.pdf';
            $filename2 = Str::slug('Lampiran ('.implode(',',$request->opt).') Executive Report Checklist Pengawasan Program ABRT-RL '.$laporan_ronde->branch_name.'-'.$laporan_ronde->round_name.'-Bulan-'.dtid::get_month_year2($laporan_ronde->created_date)).'.pdf';
            
            // cek apakah sudah pernah didownload
            if(file_exists(public_path($save_path).$save_name)) {
                // jika sudah pernah didonwload maka ambil filenya
                return response()->download(public_path($save_path).$save_name, $filename2, ['Content-Type: application/pdf']);
            }
            else {
                // jika belum pernah didownload

                $total_komponen = RondeRSModel::getTotalKomponenByBranchAssessmentId($laporan_ronde->id);
    
                if($total_komponen > 0){

                    // default
                    $rs_pembersihan     = collect([]);
                    $rs_perbaikan       = collect([]);
                    $rs_penggantian     = collect([]);
                    $rs_belum_dinilai   = collect([]);
    
                    // detail komponen
                    foreach ($request->opt as $key => $value) {
                        if($value == 'A') {
                            $rs_pembersihan     = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'A');
                        }
                        elseif($value == 'B') {
                            $rs_perbaikan       = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'B');

                        }
                        elseif($value == 'C') {
                            $rs_penggantian     = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'C');

                        }
                        elseif($value == 'D') {
                            $rs_belum_dinilai   = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,NULL);
                        }
                    }

                    // start ol
                    if($request->opt[0] == 'A') {
                        $ol_start = 1;
                    }
                    elseif($request->opt[0] == "B"){
                        $ol_start = 2;
                    }
                    elseif($request->opt[0] == "C"){
                        $ol_start = 3;
                    }
                    elseif($request->opt[0] == "D"){
                        $ol_start = 4;
                    }
                    
                    // data
                    $data = [
                        'laporan_ronde'     => $laporan_ronde,
                        'rs_pembersihan'    => $rs_pembersihan,
                        'rs_perbaikan'      => $rs_perbaikan,
                        'rs_penggantian'    => $rs_penggantian,
                        'rs_belum_dinilai'  => $rs_belum_dinilai,
                        'opt'               => implode(',',$request->opt),
                        'ol_start'          => $ol_start,
                        'vps_img_url'=> RondeRSModel::getAppSupportBy('vps_img_url')
                    ];

                    // cek folder
                    if (!is_dir(public_path($save_path))) {
                        // buat folder jika belum ada
                        mkdir(public_path($save_path), 0755, true);
                    }
                    
                    // buat dan simpan file pdf
                    $pdf = PDF::loadview('admin.validator.laporan.ronde-rs.executive-report-pdf-lampiran', $data);
                    $pdf->save(public_path($save_path).$save_name);
    
                    // ambil dan download file
                    return response()->download(public_path($save_path).$save_name, $filename2, ['Content-Type: application/pdf']);
                }
                else {
                    // flash message
                    session()->flash('danger', 'Belum ada komponen yang dinilai!');
                    return redirect('/admin/validator/laporan/ronde');
                }

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
        RondeRSModel::authorize('C');

        $pdf_template   = 'template.laporan.rekapitulasi-nilai-pdf';

        // cek regional
        if($request->m_region_name == '0'){
            // semua regional
            $rs_rumah_sakit = RondeRSModel::getListRs($request->m_ronde_id,$request->m_month,$request->m_year);
            
            // cek ronde
            if($request->m_ronde_id != '0'){
                // ronde tertentu
                $download_name  = Str::slug('EXECUTIVE REPORT NILAI SELURUH RUMAH SAKIT PENGAWASAN PROGRAM ABRT-RL RONDE-'.$request->m_ronde_id.'-Bulan-'.RondeRSModel::bulanIndo()[$request->m_month].'-Tahun-'.$request->m_year).'.pdf';
            }
            else {
                // semua ronde
                $download_name  = Str::slug('EXECUTIVE REPORT NILAI SELURUH RUMAH SAKIT PENGAWASAN PROGRAM ABRT-RL'.'-Bulan-'.RondeRSModel::bulanIndo()[$request->m_month].'-Tahun-'.$request->m_year).'.pdf';
            }
        }
        else {
            // regional tertentu
            $rs_rumah_sakit = RondeRSModel::getListRsByRegional($request->m_region_name,$request->m_ronde_id,$request->m_month,$request->m_year);
            // cek ronde
            if($request->m_ronde_id != '0'){
                // ronde teretntu
                $download_name  = Str::slug('EXECUTIVE REPORT NILAI SELURUH RUMAH SAKIT '.$request->m_region_name.' PENGAWASAN PROGRAM ABRT-RL RONDE-'.$request->m_ronde_id.'-Bulan-'.RondeRSModel::bulanIndo()[$request->m_month].'-Tahun-'.$request->m_year).'.pdf';
            }
            else{
                // semua ronde
                $download_name  = Str::slug('EXECUTIVE REPORT NILAI SELURUH RUMAH SAKIT '.$request->m_region_name.' PENGAWASAN PROGRAM ABRT-RL'.'-Bulan-'.RondeRSModel::bulanIndo()[$request->m_month].'-Tahun-'.$request->m_year).'.pdf';
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
        $arr_laporan_ronde = [];
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
                $rs_laporan_ronde = RondeRSModel::getBranchAssessmentAllRoundByParam($value->id,$request->m_month,$request->m_year);
                
                // cek
                if($rs_laporan_ronde->count() > 0){

                    // tampung nilai looping
                    $local_arr_laporan_ronde = [];
                    // loop rs laporan ronde
                    foreach ($rs_laporan_ronde as $key => $laporan_ronde) {
                        // cek viewed date
                        if(empty($laporan_ronde->validator_view_date)){
                            // update view date
                            if(!RondeRSModel::updateBranchAssessment($laporan_ronde->id, ['validator_view_date'=> date('Y-m-d H:i:s')])){
                                // nothing
                            }
                        }
        
                        // cek view dirop
                        if(!empty($laporan_ronde->dirop_view_date)){
                            // tambah ke variabel
                            $dirop_view = $dirop_view+1;
                        }
        
                        // direg
                        if($request->m_region_name != '0'){
                            // direg name
                            if(!empty($laporan_ronde->direg_name)){
                                $direg_name = $laporan_ronde->direg_name;
                            }
                            
                            // direg view
                            if(empty($laporan_ronde->direg_view_date)){
                                // update view date
                                if(!RondeRSModel::updateBranchAssessment($laporan_ronde->id, ['direg_view_date'=> date('Y-m-d H:i:s')])){
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
                    // cek viewed date
                    if(empty($laporan_ronde->validator_view_date)){
                        // update view date
                        if(!RondeRSModel::updateBranchAssessment($laporan_ronde->id, ['validator_view_date'=> date('Y-m-d H:i:s')])){
                            // nothing
                        }
                    }
    
                    // cek view dirop
                    if(!empty($laporan_ronde->dirop_view_date)){
                        // tambah ke variabel
                        $dirop_view = $dirop_view+1;
                    }
    
                    // direg
                    if($request->m_region_name != '0'){
                        // direg name
                        if(!empty($laporan_ronde->direg_name)){
                            $direg_name = $laporan_ronde->direg_name;
                        }
                        
                        // direg view
                        if(empty($laporan_ronde->direg_view_date)){
                            // update view date
                            if(!RondeRSModel::updateBranchAssessment($laporan_ronde->id, ['direg_view_date'=> date('Y-m-d H:i:s')])){
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

            $obj_summary = (object) [
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
            $obj_summary = (object) [
                'total_komponen'        => $total_komponen,
                'total_pembersihan'     => 0,
                'persen_pembersihan'    => 0,// (total_pembersihan/total_komponen)*100
                'total_perbaikan'       => 0,
                'persen_perbaikan'      => 0,// (total_perbaikan/total_komponen)*100
                'total_penggantian'     => 0,
                'persen_penggantian'    => 0,// (total_penggantian/total_komponen)*100
                'total_belum_dinilai'   => 0,
                'persen_belum_dinilai'  => 0// (total_belum_dinilai/total_komponen)*100  
            ];

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

        // -----------------------------------------------------------------------
        // signature
        $obj_signature = (object) [
            'staf_abrtrl_name'              => RondeRSModel::getAppSupportBy('staf_abrtrl_name'),
            'mpm_dep_jangum_name'           => RondeRSModel::getAppSupportBy('mpm_dep_jangum_name'),
            'kepdep_jangum_name'            => RondeRSModel::getAppSupportBy('kepdep_jangum_name'),
            'dirop_name'                    => RondeRSModel::getAppSupportBy('dirop_name'),
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
            'bulan'             => strtoupper(RondeRSModel::bulanIndo()[$request->m_month]),
            'tahun'             => $request->m_year,
            'rs_laporan_ronde'  => $coll_laporan_ronde,
            'summary'           => $obj_summary,
            'signature'         => $obj_signature,
            'bobot_penilaian'   => $bobot_penilaian,
            'region_name'       => $request->m_region_name
        ];

        // dd($data);

        $save_path = '/file/laporan-ronde/rekapitulasi/'.$request->m_year.'/';
        $save_name = 'rekapitulasi-nilai-'.date('y-m-d-H-i-s').'-'.uniqid().'.pdf';

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
    
}
