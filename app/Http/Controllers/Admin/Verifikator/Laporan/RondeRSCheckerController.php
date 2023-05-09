<?php

namespace App\Http\Controllers\Admin\Verifikator\Laporan;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Verifikator\Laporan\RondeRSModel;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Helpers\DateIndonesia as dtid;

class RondeRSVerifikatorController extends BaseController
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
            'rs_laporan_ronde'=> RondeRSModel::getAllSearch('', date('m'), date('Y')),
            'month'=> date('m'),
            'year'=> date('Y'),
            'rs_year'=> RondeRSModel::getListYear()
        ];

        // dd($data);

        // view
        return view('admin.verifikator.laporan.ronde-rs.index', $data );
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
        $query_all= $request->query_all;
        $month= $request->month;
        $year= $request->year;
        
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_laporan_ronde = RondeRSModel::getAllSearch($query_all, $month, $year);
            
            // data
            $data = [
                'rs_laporan_ronde' => $rs_laporan_ronde, 
                'query_all'=> $query_all,
                'month'=> $month,
                'year'=> $year,
                'rs_year'=> RondeRSModel::getListYear()
            ];

            // view
            return view('admin.verifikator.laporan.ronde-rs.index', $data );
        }
        else {
            return redirect('/admin/verifikator/laporan/ronde');
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
                $rs_pembersihan    = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'A');
                $rs_perbaikan      = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'B');
                $rs_penggantian    = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'C');

                // dd($rs_area);
                // data
                $data = [
                    'laporan_ronde'     =>$laporan_ronde,
                    'rs_pembersihan'    => $rs_pembersihan,
                    'rs_perbaikan'      => $rs_perbaikan,
                    'rs_penggantian'    => $rs_penggantian,
                    'rs_abc_area'       => RondeRSModel::getABCTiapAreaByBranchAssessmentId($laporan_ronde->id),
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
                    'rs_abc_area'       => RondeRSModel::getABCTiapAreaByBranchAssessmentId($laporan_ronde->id),
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
            return view('admin.verifikator.laporan.ronde-rs.executive-report-view', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/verifikator/laporan/ronde');
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
        
        // if exist
        if(!empty($laporan_ronde)) {

            $total_komponen = RondeRSModel::getTotalKomponenByBranchAssessmentId($laporan_ronde->id);

            if($total_komponen > 0){
                // score
                $total_pembersihan  = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'A');
                $total_perbaikan    = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'B');
                $total_penggantian  = RondeRSModel::getTotalKomponenScoreByBranchAssessmentId($laporan_ronde->id,'C');
        
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
                $rs_pembersihan    = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'A');
                $rs_perbaikan      = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'B');
                $rs_penggantian    = RondeRSModel::getListItemByBranchAssessmentId($laporan_ronde->id,'C');
                
                $data = [
                    'laporan_ronde'     =>$laporan_ronde,
                    'rs_pembersihan'    => $rs_pembersihan,
                    'rs_perbaikan'      => $rs_perbaikan,
                    'rs_penggantian'    => $rs_penggantian,
                    'rs_abc_area'       => RondeRSModel::getABCTiapAreaByBranchAssessmentId($laporan_ronde->id),
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
                    'rs_abc_area'       => RondeRSModel::getABCTiapAreaByBranchAssessmentId($laporan_ronde->id),
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
            $pdf = PDF::loadview('admin.verifikator.laporan.ronde-rs.executive-report-pdf', $data);
            $filename = Str::slug('Executive Report Checklist Pengawasan Program ABRT-RL '.$laporan_ronde->round_name.'-Bulan-'.dtid::get_month_year($laporan_ronde->created_date)).'.pdf';
    	    return $pdf->download($filename);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/verifikator/laporan/ronde');
        }
    }


}
