<?php

namespace App\Http\Controllers\Admin\Checker\Laporan;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Checker\Laporan\AllRondeModel;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Helpers\DateIndonesia as dtid;

class AllRondeCheckerController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        AllRondeModel::authorize('R');

        $data = [
            'rs_laporan_ronde'=> AllRondeModel::getAllSearch('', date('m'), date('Y')),
            'month'=> date('m'),
            'year'=> date('Y'),
            'rs_year'=> AllRondeModel::getListYear()
        ];

        // dd($data);

        // view
        return view('admin.checker.laporan.semua-ronde.index', $data );
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
        AllRondeModel::authorize('R');

        // data request
        $query_all= $request->query_all;
        $month= $request->month;
        $year= $request->year;
        
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_laporan_ronde = AllRondeModel::getAllSearch($query_all, $month, $year);
            
            // data
            $data = [
                'rs_laporan_ronde' => $rs_laporan_ronde, 
                'query_all'=> $query_all,
                'month'=> $month,
                'year'=> $year,
                'rs_year'=> AllRondeModel::getListYear()
            ];

            // view
            return view('admin.checker.laporan.semua-ronde.index', $data );
        }
        else {
            return redirect('/admin/checker/laporan/ronde');
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
        AllRondeModel::authorize('R');

        // get data
        $laporan_ronde = AllRondeModel::getById($id);
        $rs_assessment   = AllRondeModel::getAllItem($laporan_ronde->id,$laporan_ronde->round_id);
        // if exist
        if(!empty($laporan_ronde)) {

            $total_komponen = AllRondeModel::getTotalKomponenByBranchAssessmentId($laporan_ronde->id);

            if($total_komponen > 0){

                // score
                $total_pembersihan  = AllRondeModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'A');
                $total_perbaikan    = AllRondeModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'B');
                $total_penggantian  = AllRondeModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'C');
        
                // parameter
                $total_aman         = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Aman');
                $total_bersih       = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Bersih');
                $total_rapih        = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Rapih');
                $total_tampak_baru  = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tampak Baru');
                $total_ramah_lingkungan = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Ramah Lingkungan');
                $total_tidak_aman   = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Aman');
                $total_tidak_bersih = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Bersih');
                $total_tidak_rapih  = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Rapih');
                $total_tidak_tampak_baru = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Tampak Baru');
                $total_tidak_ramah_lingkungan = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Ramah Lingkungan');

                // detail komponen
                $rs_pembersihan    = AllRondeModel::getListItemByBranchAssessmentId($laporan_ronde->id,'A');
                $rs_perbaikan      = AllRondeModel::getListItemByBranchAssessmentId($laporan_ronde->id,'B');
                $rs_penggantian    = AllRondeModel::getListItemByBranchAssessmentId($laporan_ronde->id,'C');
                

                
                // data
                $data = [
                    'api_img'           => env('API_IMG'),
                    'laporan_ronde'     =>$laporan_ronde,
                    'rs_assessment'     =>$rs_assessment,
                    'rs_pembersihan'    => $rs_pembersihan,
                    'rs_perbaikan'      => $rs_perbaikan,
                    'rs_penggantian'    => $rs_penggantian,
                    'rs_abc_area'       => AllRondeModel::getABCTiapAreaByBranchAssessmentId($laporan_ronde->id),
                    'bobot_penilaian' => [
                        // ((total_perbaikan*1)/total_komponen)*100
                        'persen_pembersihan'=> (($total_pembersihan*1)/$total_komponen)*100,
                        // ((total_perbaikan*0.5)/total_komponen)*100
                        'persen_perbaikan'=> (($total_perbaikan*0.5)/$total_komponen)*100,
                        // ((total_perbaikan*0.25)/total_komponen)*100
                        'persen_penggantian'=> (($total_penggantian*0.25)/$total_komponen)*100,
                        'persen_abrt_rl'=> ((($total_pembersihan*1)+($total_perbaikan*0.5)+($total_penggantian*0.25))/$total_komponen)*100
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
                        'persen_penggantian'=> ($total_penggantian/$total_komponen)*100// (total_penggantian/total_komponen)*100 
                    ]
                ];
            }
            else {
                $data = [
                    'laporan_ronde'     => $laporan_ronde,
                    'rs_assessment'     =>$rs_assessment,
                    'rs_pembersihan'    => collect([]),
                    'rs_perbaikan'      => collect([]),
                    'rs_penggantian'    => collect([]),
                    'rs_abc_area'       => AllRondeModel::getABCTiapAreaByBranchAssessmentId($laporan_ronde->id),
                    'bobot_penilaian' => [
                        // ((total_perbaikan*1)/total_komponen)*100
                        'persen_pembersihan'=> 0,
                        // ((total_perbaikan*0.5)/total_komponen)*100
                        'persen_perbaikan'=> 0,
                        // ((total_perbaikan*0.25)/total_komponen)*100
                        'persen_penggantian'=> 0,
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
                        'persen_penggantian'=> 0// (total_penggantian/total_komponen)*100 
                    ]
                ];
            }

            //view
            // dd($rs_assessment);
            return view('admin.checker.laporan.semua-ronde.detail', $data);
        }
        else {
            // $data = ['rs_assessment' =>$rs_assessment,];
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/checker/laporan/semua-ronde');
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
        AllRondeModel::authorize('R');

        // get data
        $laporan_ronde = AllRondeModel::getById($id);
        
        // if exist
        if(!empty($laporan_ronde)) {

            $total_komponen = AllRondeModel::getTotalKomponenByBranchAssessmentId($laporan_ronde->id);

            if($total_komponen > 0){
                // score
                $total_pembersihan  = AllRondeModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'A');
                $total_perbaikan    = AllRondeModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'B');
                $total_penggantian  = AllRondeModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'C');
        
                // parameter
                $total_aman         = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Aman');
                $total_bersih       = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Bersih');
                $total_rapih        = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Rapih');
                $total_tampak_baru  = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tampak Baru');
                $total_ramah_lingkungan = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Ramah Lingkungan');
                $total_tidak_aman   = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Aman');
                $total_tidak_bersih = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Bersih');
                $total_tidak_rapih  = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Rapih');
                $total_tidak_tampak_baru = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Tampak Baru');
                $total_tidak_ramah_lingkungan = AllRondeModel::getTotalKomponenParameterByBranchAssessmentId($laporan_ronde->id,'Tidak Ramah Lingkungan');

                // detail komponen
                $rs_pembersihan    = AllRondeModel::getListItemByBranchAssessmentId($laporan_ronde->id,'A');
                $rs_perbaikan      = AllRondeModel::getListItemByBranchAssessmentId($laporan_ronde->id,'B');
                $rs_penggantian    = AllRondeModel::getListItemByBranchAssessmentId($laporan_ronde->id,'C');
                
                $data = [
                    'laporan_ronde'     =>$laporan_ronde,
                    'rs_pembersihan'    => $rs_pembersihan,
                    'rs_perbaikan'      => $rs_perbaikan,
                    'rs_penggantian'    => $rs_penggantian,
                    'rs_abc_area'       => AllRondeModel::getABCTiapAreaByBranchAssessmentId($laporan_ronde->id),
                    'bobot_penilaian' => [
                        // ((total_perbaikan*1)/total_komponen)*100
                        'persen_pembersihan'=> (($total_pembersihan*1)/$total_komponen)*100,
                        // ((total_perbaikan*0.5)/total_komponen)*100
                        'persen_perbaikan'=> (($total_perbaikan*0.5)/$total_komponen)*100,
                        // ((total_perbaikan*0.25)/total_komponen)*100
                        'persen_penggantian'=> (($total_penggantian*0.25)/$total_komponen)*100,
                        'persen_abrt_rl'=> ((($total_pembersihan*1)+($total_perbaikan*0.5)+($total_penggantian*0.25))/$total_komponen)*100
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
                        'persen_penggantian'=> ($total_penggantian/$total_komponen)*100// (total_penggantian/total_komponen)*100 
                    ]
                ];
            }
            else {
                $data = [
                    'laporan_ronde'     => $laporan_ronde,
                    'rs_pembersihan'    => collect([]),
                    'rs_perbaikan'      => collect([]),
                    'rs_penggantian'    => collect([]),
                    'rs_abc_area'       => AllRondeModel::getABCTiapAreaByBranchAssessmentId($laporan_ronde->id),
                    'bobot_penilaian' => [
                        // ((total_perbaikan*1)/total_komponen)*100
                        'persen_pembersihan'=> 0,
                        // ((total_perbaikan*0.5)/total_komponen)*100
                        'persen_perbaikan'=> 0,
                        // ((total_perbaikan*0.25)/total_komponen)*100
                        'persen_penggantian'=> 0,
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
                        'persen_penggantian'=> 0// (total_penggantian/total_komponen)*100 
                    ]
                ];
            }

            //view
            $pdf = PDF::loadview('admin.checker.laporan.semua-ronde.executive-report-pdf', $data);
            $filename = Str::slug('Executive Report Checklist Pengawasan Program ABRT-RL '.$laporan_ronde->round_name.'-Bulan-'.dtid::get_month_year($laporan_ronde->created_date)).'.pdf';
    	    return $pdf->download($filename);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/checker/laporan/ronde');
        }
    }


}
