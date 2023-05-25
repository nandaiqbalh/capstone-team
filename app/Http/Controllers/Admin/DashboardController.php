<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Models\Admin\DashboardModel as Dashmo;
use Illuminate\Support\Facades\Auth;

class DashboardController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        Dashmo::authorize('R');
        Dashmo::authorize('R');

        // get data with pagination
        $rs_tenant = Dashmo::getTenantSaldoAll();
        // dd($saldo);

        
        // data

        $data = [
            'rs_tenant' => $rs_tenant,
            'rs_transaction' => collect($data_transaksi_detail),
            'semua_saldo' => array_sum($saldo_all),
            'saldo_tenant' => array_sum($saldo_tenant),
            'margin_aba' => array_sum($margin_aba_arr),
            'margin_olk' => array_sum($margin_olk_arr),
            'saldo_markup' => array_sum($saldo_markup),
            'saldo_ppn' => array_sum($saldo_ppn),
        ];

        //view
        return view('admin.dashboard.index', $data);
    }

    // -------------------------------------------------
    /**
     * VALIDATOR
     * ajax
     */
    public function manajer() {
        // authorize
        Dashmo::authorize('R');

        $data = [
            'arr_rekapitulasi_nilai_tertinggi_terendah'     => Dashmo::validatorRekapitulasiNilai3TeringgiTerendah(),
            'arr_rekapitulasi_parameter'                    => Dashmo::validatorRekapitulasiParameter(),
            'arr_rekapitulasi_terlambat_submit'             => Dashmo::validatorGetTerlambatSubmit(),
            'target_rata_nilai'                             => intval(Dashmo::validatorGetTargetRataNilai()),
            'max_terlambat_submit'                          => intval(Dashmo::getListBranch('')->count())
        ];

        return response()->json($data)->setStatusCode(200);
    }

    public function validatorDataPekerjaan() {
        // authorize
        Dashmo::authorize('R');

        // list bulan
        $rs_bulan = Dashmo::bulanIndo();

        // // semua perbaikan & pekerjaan
        // // get list total pekerjaan
        // $arr_total                  = [];
        // $arr_presentase_selesai          = [];
        // $arr_presentase_belum_dikerjakan = [];
        // // loop
        // foreach($rs_bulan as $key => $bulan){
        //     // get data
        //     $data = Dashmo::validatorTotalPekerjaanTiapBulanByTahun($key, date('Y'));
            
        //     // hitung presentase
        //     if($data->jumlah_total != 0){
        //         $presentase_selesai             = ($data->jumlah_selesai/$data->jumlah_total)*100;
        //         $presentase_belum_dikerjakan    = ($data->jumlah_belum_dikerjakan/$data->jumlah_total)*100;
        //     }
        //     else {
        //         $presentase_selesai             = 0;
        //         $presentase_belum_dikerjakan    = 0;
        //     }

        //     // push
        //     array_push($arr_total, $data->jumlah_total);
        //     array_push($arr_presentase_selesai, round($presentase_selesai,2));
        //     array_push($arr_presentase_belum_dikerjakan, round($presentase_belum_dikerjakan,2));
        // }

        // -------------------------------------------------------------------------------------------
        // perbaikan
        // get list total pekerjaan
        $arr_total_perbaikan                          = [];
        $arr_presentase_selesai_perbaikan             = [];
        $arr_presentase_belum_dikerjakan_perbaikan    = [];
        // loop
        foreach($rs_bulan as $key => $bulan){
            // get data
            $perbaikan = Dashmo::validatorTotalPekerjaanTiapBulanByTahunByScore($key, date('Y'), 'B');
            
            // hitung presentase
            if($perbaikan->jumlah_total != 0){
                $presentase_selesai             = ($perbaikan->jumlah_selesai/$perbaikan->jumlah_total)*100;
                $presentase_belum_dikerjakan    = ($perbaikan->jumlah_belum_dikerjakan/$perbaikan->jumlah_total)*100;
            }
            else {
                $presentase_selesai             = 0;
                $presentase_belum_dikerjakan    = 0;
            }

            // push
            array_push($arr_total_perbaikan, $perbaikan->jumlah_total);
            array_push($arr_presentase_selesai_perbaikan, round($presentase_selesai,2));
            array_push($arr_presentase_belum_dikerjakan_perbaikan, round($presentase_belum_dikerjakan,2));
        }

        // -------------------------------------------------------------------------------------------
        // penggantian
        // get list total pekerjaan
        $arr_total_penggantian                          = [];
        $arr_presentase_selesai_penggantian             = [];
        $arr_presentase_belum_dikerjakan_penggantian    = [];
        // loop
        foreach($rs_bulan as $key => $bulan){
            // get data
            $penggantian = Dashmo::validatorTotalPekerjaanTiapBulanByTahunByScore($key, date('Y'), 'C');
            
            // hitung presentase
            if($penggantian->jumlah_total != 0){
                $presentase_selesai             = ($penggantian->jumlah_selesai/$penggantian->jumlah_total)*100;
                $presentase_belum_dikerjakan    = ($penggantian->jumlah_belum_dikerjakan/$penggantian->jumlah_total)*100;
            }
            else {
                $presentase_selesai             = 0;
                $presentase_belum_dikerjakan    = 0;
            }

            // push
            array_push($arr_total_penggantian, $penggantian->jumlah_total);
            array_push($arr_presentase_selesai_penggantian, round($presentase_selesai,2));
            array_push($arr_presentase_belum_dikerjakan_penggantian, round($presentase_belum_dikerjakan,2));
        }

        $data = [
            // 'arr_selesai'           => $arr_presentase_selesai,
            // 'arr_belum_dikerjakan'  => $arr_presentase_belum_dikerjakan,
            'arr_total_perbaikan'                         => $arr_total_perbaikan                       ,
            'arr_presentase_selesai_perbaikan'            => $arr_presentase_selesai_perbaikan          ,
            'arr_presentase_belum_dikerjakan_perbaikan'   => $arr_presentase_belum_dikerjakan_perbaikan ,
            'arr_total_penggantian'                         => $arr_total_penggantian                       ,
            'arr_presentase_selesai_penggantian'            => $arr_presentase_selesai_penggantian          ,
            'arr_presentase_belum_dikerjakan_penggantian'   => $arr_presentase_belum_dikerjakan_penggantian 
        ];

        return response()->json($data)->setStatusCode(200);
    }

    public function validatorDataPekerjaanTerlambatPersetujuan() {
        // authorize
        Dashmo::authorize('R');

        $data = [
            'arr_rekapitulasi_terlambat_persetujuan'             => Dashmo::validatorGetPekerjaanTerlambatPersetujuan(),
            'max_terlambat_persetujuan'                          => intval(Dashmo::getListBranch('')->count())
        ];

        return response()->json($data)->setStatusCode(200);
    }

    // -------------------------------------------------
    /**
     * HOLDING OPERASIONAL
     */
    public function holdingOperasional() {
        // authorize
        Dashmo::authorize('R');

        $data = [
            'arr_rekapitulasi_nilai_tertinggi_terendah' => Dashmo::HoldingOpRekapitulasiNilai3TeringgiTerendah(),
            'arr_rekapitulasi_parameter'                => Dashmo::HoldingOpRekapitulasiParameter(),
            'arr_rekapitulasi_terlambat_submit'         => Dashmo::HoldingOpGetTerlambatSubmit(),
            'target_rata_nilai'                         => intval(Dashmo::HoldingOpGetTargetRataNilai()),
            'max_terlambat_submit'                      => intval(Dashmo::getListBranch('')->count())
        ];

        return response()->json($data)->setStatusCode(200);
    }

    // -------------------------------------------------
    /**
     * HOLDING REGIONAL
     */
    public function holdingRegional() {
        // authorize
        Dashmo::authorize('R');

        $data = [
            'arr_rekapitulasi_nilai_tertinggi_terendah' => Dashmo::HoldingRgRekapitulasiNilai3TeringgiTerendah(),
            'arr_rekapitulasi_parameter'                => Dashmo::HoldingRgRekapitulasiParameter(),
            'arr_rekapitulasi_terlambat_submit'         => Dashmo::HoldingRgGetTerlambatSubmit()
        ];

        return response()->json($data)->setStatusCode(200);
    }

    public function holdingRegionalDataPekerjaan() {
        // authorize
        Dashmo::authorize('R');

        // list bulan
        $rs_bulan = Dashmo::bulanIndo();

        // -------------------------------------------------------------------------------------------
        // perbaikan
        // get list total pekerjaan
        $arr_total_perbaikan                          = [];
        $arr_presentase_selesai_perbaikan             = [];
        $arr_presentase_belum_dikerjakan_perbaikan    = [];
        // loop
        foreach($rs_bulan as $key => $bulan){
            // get data
            $perbaikan = Dashmo::holdingRgTotalPekerjaanTiapBulanByTahunByScore($key, date('Y'), 'B');
            
            // hitung presentase
            if($perbaikan->jumlah_total != 0){
                $presentase_selesai             = ($perbaikan->jumlah_selesai/$perbaikan->jumlah_total)*100;
                $presentase_belum_dikerjakan    = ($perbaikan->jumlah_belum_dikerjakan/$perbaikan->jumlah_total)*100;
            }
            else {
                $presentase_selesai             = 0;
                $presentase_belum_dikerjakan    = 0;
            }

            // push
            array_push($arr_total_perbaikan, $perbaikan->jumlah_total);
            array_push($arr_presentase_selesai_perbaikan, round($presentase_selesai,2));
            array_push($arr_presentase_belum_dikerjakan_perbaikan, round($presentase_belum_dikerjakan,2));
        }

        // -------------------------------------------------------------------------------------------
        // penggantian
        // get list total pekerjaan
        $arr_total_penggantian                          = [];
        $arr_presentase_selesai_penggantian             = [];
        $arr_presentase_belum_dikerjakan_penggantian    = [];
        // loop
        foreach($rs_bulan as $key => $bulan){
            // get data
            $penggantian = Dashmo::holdingRgTotalPekerjaanTiapBulanByTahunByScore($key, date('Y'), 'C');
            
            // hitung presentase
            if($penggantian->jumlah_total != 0){
                $presentase_selesai             = ($penggantian->jumlah_selesai/$penggantian->jumlah_total)*100;
                $presentase_belum_dikerjakan    = ($penggantian->jumlah_belum_dikerjakan/$penggantian->jumlah_total)*100;
            }
            else {
                $presentase_selesai             = 0;
                $presentase_belum_dikerjakan    = 0;
            }

            // push
            array_push($arr_total_penggantian, $penggantian->jumlah_total);
            array_push($arr_presentase_selesai_penggantian, round($presentase_selesai,2));
            array_push($arr_presentase_belum_dikerjakan_penggantian, round($presentase_belum_dikerjakan,2));
        }

        $data = [
            // 'arr_selesai'           => $arr_presentase_selesai,
            // 'arr_belum_dikerjakan'  => $arr_presentase_belum_dikerjakan,
            'arr_total_perbaikan'                         => $arr_total_perbaikan                       ,
            'arr_presentase_selesai_perbaikan'            => $arr_presentase_selesai_perbaikan          ,
            'arr_presentase_belum_dikerjakan_perbaikan'   => $arr_presentase_belum_dikerjakan_perbaikan ,
            'arr_total_penggantian'                         => $arr_total_penggantian                       ,
            'arr_presentase_selesai_penggantian'            => $arr_presentase_selesai_penggantian          ,
            'arr_presentase_belum_dikerjakan_penggantian'   => $arr_presentase_belum_dikerjakan_penggantian 
        ];

        return response()->json($data)->setStatusCode(200);
    }

    public function holdingRegionalDataPekerjaanTerlambatPersetujuan() {
        // authorize
        Dashmo::authorize('R');

        $data = [
            'arr_rekapitulasi_terlambat_persetujuan'             => Dashmo::holdingRgGetPekerjaanTerlambatPersetujuan(),
            'max_terlambat_persetujuan'                          => intval(Dashmo::getListBranchByRegional('Regional '.Auth::user()->region_id)->count())
        ];

        return response()->json($data)->setStatusCode(200);
    }

    // -------------------------------------------------
    // cheker 
    public function checkerDashboard(){
        // Rekapitulasi Nilai Checker 
        $getNilaiCheckerAll = Dashmo::getDataNilaiAll();
        $data = ['target_rata_nilai'=> intval(Dashmo::validatorGetTargetRataNilai()),];
        //Progres Nilai Checker
        if ($getNilaiCheckerAll > 0) {
            //get nilai checker R1
            $getNilaiCheckerR1 = [];
            foreach (Dashmo::getDataNilaiR1()->toArray() as $key => $value) {
                if (Dashmo::getDataNilaiR1()->toArray()[$key]->score == "A") {
                    $getNilaiCheckerR1["A"] = Dashmo::getDataNilaiR1()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("A",$getNilaiCheckerR1)) {
                        $getNilaiCheckerR1["A"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR1()->toArray()[$key]->score == "B") {
                    $getNilaiCheckerR1["B"] = Dashmo::getDataNilaiR1()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("B",$getNilaiCheckerR1)) {
                        $getNilaiCheckerR1["B"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR1()->toArray()[$key]->score == "C") {
                    $getNilaiCheckerR1["C"] = Dashmo::getDataNilaiR1()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("C",$getNilaiCheckerR1)) {
                        $getNilaiCheckerR1["C"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR1()->toArray()[$key]->score == Null) {
                    $getNilaiCheckerR1["Null"] = Dashmo::getDataNilaiR1()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("Null",$getNilaiCheckerR1)) {
                        $getNilaiCheckerR1["Null"] = 0;
                    }
                }
            }
            // dd($getNilaiCheckerR1);
            //get nilai checker R2
            $getNilaiCheckerR2 = [];
            foreach (Dashmo::getDataNilaiR2()->toArray() as $key => $value) {
                if (Dashmo::getDataNilaiR2()->toArray()[$key]->score == "A") {
                    $getNilaiCheckerR2["A"] = Dashmo::getDataNilaiR2()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("A",$getNilaiCheckerR2)) {
                        $getNilaiCheckerR2["A"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR2()->toArray()[$key]->score == "B") {
                    $getNilaiCheckerR2["B"] = Dashmo::getDataNilaiR2()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("B",$getNilaiCheckerR2)) {
                        $getNilaiCheckerR2["B"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR2()->toArray()[$key]->score == "C") {
                    $getNilaiCheckerR2["C"] = Dashmo::getDataNilaiR2()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("C",$getNilaiCheckerR2)) {
                        $getNilaiCheckerR2["C"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR2()->toArray()[$key]->score == Null) {
                    $getNilaiCheckerR2["Null"] = Dashmo::getDataNilaiR2()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("Null",$getNilaiCheckerR2)) {
                        $getNilaiCheckerR2["Null"] = 0;
                    }
                }
            }


            //get nilai checker R3
            $getNilaiCheckerR3 = [];
            foreach (Dashmo::getDataNilaiR3()->toArray() as $key => $value) {
                if (Dashmo::getDataNilaiR3()->toArray()[$key]->score == "A") {
                    $getNilaiCheckerR3["A"] = Dashmo::getDataNilaiR3()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("A",$getNilaiCheckerR3)) {
                        $getNilaiCheckerR3["A"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR3()->toArray()[$key]->score == "B") {
                    $getNilaiCheckerR3["B"] = Dashmo::getDataNilaiR3()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("B",$getNilaiCheckerR3)) {
                        $getNilaiCheckerR3["B"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR3()->toArray()[$key]->score == "C") {
                    $getNilaiCheckerR3["C"] = Dashmo::getDataNilaiR3()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("C",$getNilaiCheckerR3)) {
                        $getNilaiCheckerR3["C"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR3()->toArray()[$key]->score == Null) {
                    $getNilaiCheckerR3["Null"] = Dashmo::getDataNilaiR3()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("Null",$getNilaiCheckerR3)) {
                        $getNilaiCheckerR3["Null"] = 0;
                    }
                }
            }

            //get nilai checker R4
            $getNilaiCheckerR4 = [];
            foreach (Dashmo::getDataNilaiR4()->toArray() as $key => $value) {
                if (Dashmo::getDataNilaiR4()->toArray()[$key]->score == "A") {
                    $getNilaiCheckerR4["A"] = Dashmo::getDataNilaiR4()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("A",$getNilaiCheckerR4)) {
                        $getNilaiCheckerR4["A"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR4()->toArray()[$key]->score == "B") {
                    $getNilaiCheckerR4["B"] = Dashmo::getDataNilaiR4()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("B",$getNilaiCheckerR4)) {
                        $getNilaiCheckerR4["B"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR4()->toArray()[$key]->score == "C") {
                    $getNilaiCheckerR4["C"] = Dashmo::getDataNilaiR4()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("C",$getNilaiCheckerR4)) {
                        $getNilaiCheckerR4["C"] = 0;
                    }
                }
                if (Dashmo::getDataNilaiR4()->toArray()[$key]->score == Null) {
                    $getNilaiCheckerR4["Null"] = Dashmo::getDataNilaiR4()->toArray()[$key]->total;
                }
                else {
                    if (!array_key_exists("Null",$getNilaiCheckerR4)) {
                        $getNilaiCheckerR4["Null"] = 0;
                    }
                }
            }


            $data['nilai_checker_R1'] =[
                round((!array_key_exists("A",$getNilaiCheckerR1)) ? (0) : (($getNilaiCheckerR1["A"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR1()[0]->round_id))*100),2),
                round((!array_key_exists("B",$getNilaiCheckerR1)) ? (0) : (($getNilaiCheckerR1["B"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR1()[0]->round_id))*100),2),
                round((!array_key_exists("C",$getNilaiCheckerR1)) ? (0) : (($getNilaiCheckerR1["C"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR1()[0]->round_id))*100),2),
                round((!array_key_exists("Null",$getNilaiCheckerR1)) ? (0) : (($getNilaiCheckerR1["Null"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR1()[0]->round_id))*100),2)
            ];
            $data['nilai_checker_R2'] =[
                round((!array_key_exists("A",$getNilaiCheckerR2)) ? (0) : ($getNilaiCheckerR2["A"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR2()[0]->round_id))*100,2),
                round((!array_key_exists("B",$getNilaiCheckerR2)) ? (0) : ($getNilaiCheckerR2["B"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR2()[0]->round_id))*100,2),
                round((!array_key_exists("C",$getNilaiCheckerR2)) ? (0) : ($getNilaiCheckerR2["C"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR2()[0]->round_id))*100,2),
                round((!array_key_exists("Null",$getNilaiCheckerR2)) ? (0) : ($getNilaiCheckerR2["Null"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR2()[0]->round_id))*100,2)
            ];
            $data['nilai_checker_R3'] =[
                round((!array_key_exists("A",$getNilaiCheckerR3)) ? (0) : ($getNilaiCheckerR3["A"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR3()[0]->round_id))*100,2),
                round((!array_key_exists("B",$getNilaiCheckerR3)) ? (0) : ($getNilaiCheckerR3["B"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR3()[0]->round_id))*100,2),
                round((!array_key_exists("C",$getNilaiCheckerR3)) ? (0) : ($getNilaiCheckerR3["C"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR3()[0]->round_id))*100,2),
                round((!array_key_exists("Null",$getNilaiCheckerR3)) ? (0) : ($getNilaiCheckerR3["Null"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR3()[0]->round_id))*100,2),
            ];
            $data['nilai_checker_R4'] =[
                round((!array_key_exists("A",$getNilaiCheckerR4)) ? (0) : ($getNilaiCheckerR4["A"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR4()[0]->round_id))*100,2),
                round((!array_key_exists("B",$getNilaiCheckerR4)) ? (0) : ($getNilaiCheckerR4["B"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR4()[0]->round_id))*100,2),
                round((!array_key_exists("C",$getNilaiCheckerR4)) ? (0) : ($getNilaiCheckerR4["C"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR4()[0]->round_id))*100,2),
                round((!array_key_exists("Null",$getNilaiCheckerR4)) ? (0) : ($getNilaiCheckerR4["Null"] / Dashmo::getNilaiCheckerAllRound(Dashmo::getDataNilaiR4()[0]->round_id))*100,2),
            ];


            // $getNilaiCheckerR3 = Dashmo::getDataNilaiR3()->toArray();
            // $getNilaiCheckerR4 = Dashmo::getDataNilaiR4()->toArray();

        }
        else {
            $data['nilai_checker_R1'] = [0,0,0];
            $data['nilai_checker_R2'] = [0,0,0];
            $data['nilai_checker_R3'] = [0,0,0];
            $data['nilai_checker_R4'] = [0,0,0];
        }
        
        return response()->json($data)->setStatusCode(200);
    }

    // cheker 
    public function checkerDashboard2(){
    
            
            //==========================================
            //Progres Capaian Nilai 
            $rs_bulan = Dashmo::bulanIndo();
            
            $arrBulan = [];
            $data = ['target_rata_nilai'=> intval(Dashmo::validatorGetTargetRataNilai()),];
            // dd($arrBulan,Dashmo::getNilaiBulan('08'));
            foreach ($rs_bulan as $key => $value) {
                $scoreMonth = [];
                $getNilaiAllYear = Dashmo::getNilaiAllYear($key);
                if ($getNilaiAllYear) {
                    foreach (Dashmo::getNilaiBulan($key) as $keyBulan => $valueBulan) {
                        # code...
                        if ($valueBulan->score == 'A') {
                            $score = 1;
                        }
                        elseif ($valueBulan->score == 'B') {
                            $score = 0.5;
                        }
                        elseif ($valueBulan->score == 'C') {
                            $score = 0.25;
                        }
                        else {
                            $score = 0;
                        }
                        array_push($scoreMonth,$score);
                    }
                    $total_score = array_sum($scoreMonth);
                    Dashmo::getNilaiBulan($key)->count();
                    $arrBulan[] = (!Dashmo::getNilaiBulan($key)) ? (0) : 
                    round((($total_score)/$getNilaiAllYear)*100,2) ;   
                    // dd(array_sum($scoreMonth));
                }
                else {
                    $arrBulan[] = 0;
                }
                
                  
            }
            
            $data['nilai_bulan']=$arrBulan;


            //parameter per bulan
            // $data = [];
            //parameter aman R1
            $getParameterAmanR1 = Dashmo::getParameterAmanR1();
            $getParameterAmanR2 = Dashmo::getParameterAmanR2();
            $getParameterAmanR3 = Dashmo::getParameterAmanR3();
            $getParameterAmanR4 = Dashmo::getParameterAmanR4();
            // dd($getParameterAmanR1,$getParameterAmanR2,$getParameterAmanR3,$getParameterAmanR4);
            //parameter aBersihR1
            $getParameterBersihR1 = Dashmo::getParameterBersihR1();
            $getParameterBersihR2 = Dashmo::getParameterBersihR2();
            $getParameterBersihR3 = Dashmo::getParameterBersihR3();
            $getParameterBersihR4 = Dashmo::getParameterBersihR4();
            //parameter aRapihR1
            $getParameterRapihR1 = Dashmo::getParameterRapihR1();
            $getParameterRapihR2 = Dashmo::getParameterRapihR2();
            $getParameterRapihR3 = Dashmo::getParameterRapihR3();
            $getParameterRapihR4 = Dashmo::getParameterRapihR4();
            //parameter Tampak Baru R1
            $getParameterTampakBaruR1 = Dashmo::getParameterTampakBaruR1();
            $getParameterTampakBaruR2 = Dashmo::getParameterTampakBaruR2();
            $getParameterTampakBaruR3 = Dashmo::getParameterTampakBaruR3();
            $getParameterTampakBaruR4 = Dashmo::getParameterTampakBaruR4();
            //parameter RamahLingkunganBaru
            $getParameterRamahLingkunganR1 = Dashmo::getParameterRamahLingkunganR1();
            $getParameterRamahLingkunganR2 = Dashmo::getParameterRamahLingkunganR2();
            $getParameterRamahLingkunganR3 = Dashmo::getParameterRamahLingkunganR3();
            $getParameterRamahLingkunganR4 = Dashmo::getParameterRamahLingkunganR4();
            //parameter aman R1
            $getParameterTidakAmanR1 = Dashmo::getParameterTidakAmanR1();
            $getParameterTidakAmanR2 = Dashmo::getParameterTidakAmanR2();
            $getParameterTidakAmanR3 = Dashmo::getParameterTidakAmanR3();
            $getParameterTidakAmanR4 = Dashmo::getParameterTidakAmanR4();
            // dd($getParameterAmanR1,$getParameterAmanR2,$getParameterAmanR3,$getParameterAmanR4);
            //parameter aBersihR1
            $getParameterTidakBersihR1 = Dashmo::getParameterTidakBersihR1();
            $getParameterTidakBersihR2 = Dashmo::getParameterTidakBersihR2();
            $getParameterTidakBersihR3 = Dashmo::getParameterTidakBersihR3();
            $getParameterTidakBersihR4 = Dashmo::getParameterTidakBersihR4();
            //parameter aRapihR1
            $getParameterTidakRapihR1 = Dashmo::getParameterTidakRapihR1();
            $getParameterTidakRapihR2 = Dashmo::getParameterTidakRapihR2();
            $getParameterTidakRapihR3 = Dashmo::getParameterTidakRapihR3();
            $getParameterTidakRapihR4 = Dashmo::getParameterTidakRapihR4();
            //parameter Tampak Baru R1
            $getParameterTidakTampakBaruR1 = Dashmo::getParameterTidakTampakBaruR1();
            $getParameterTidakTampakBaruR2 = Dashmo::getParameterTidakTampakBaruR2();
            $getParameterTidakTampakBaruR3 = Dashmo::getParameterTidakTampakBaruR3();
            $getParameterTidakTampakBaruR4 = Dashmo::getParameterTidakTampakBaruR4();
            //parameter RamahLingkunganBaru
            $getParameterTidakRamahLingkunganR1 = Dashmo::getParameterTidakRamahLingkunganR1();
            $getParameterTidakRamahLingkunganR2 = Dashmo::getParameterTidakRamahLingkunganR2();
            $getParameterTidakRamahLingkunganR3 = Dashmo::getParameterTidakRamahLingkunganR3();
            $getParameterTidakRamahLingkunganR4 = Dashmo::getParameterTidakRamahLingkunganR4();

            $data['parameter_aman'] = [
                round($getParameterAmanR1/($getParameterAmanR1+$getParameterTidakAmanR1+0.00000000001)*100,2),
                round($getParameterAmanR2/($getParameterAmanR2+$getParameterTidakAmanR2+0.00000000001)*100,2),
                round($getParameterAmanR3/($getParameterAmanR3+$getParameterTidakAmanR3+0.00000000001)*100,2),
                round($getParameterAmanR4/($getParameterAmanR4+$getParameterTidakAmanR4+0.00000000001)*100,2),
            ];
            $data['parameter_bersih'] = [
                round($getParameterBersihR1/($getParameterBersihR1+$getParameterTidakBersihR1+0.00000000001)*100,2),
                round($getParameterBersihR2/($getParameterBersihR2+$getParameterTidakBersihR2+0.00000000001)*100,2),
                round($getParameterBersihR3/($getParameterBersihR3+$getParameterTidakBersihR3+0.00000000001)*100,2),
                round($getParameterBersihR4/($getParameterBersihR4+$getParameterTidakBersihR4+0.00000000001)*100,2),
            ];
            $data['parameter_rapih'] = [
                round($getParameterRapihR1/($getParameterRapihR1+$getParameterTidakRapihR1+0.00000000001)*100,2),
                round($getParameterRapihR2/($getParameterRapihR2+$getParameterTidakRapihR2+0.00000000001)*100,2),
                round($getParameterRapihR3/($getParameterRapihR3+$getParameterTidakRapihR3+0.00000000001)*100,2),
                round($getParameterRapihR4/($getParameterRapihR4+$getParameterTidakRapihR4+0.00000000001)*100,2),
            ];
            $data['parameter_tampak_baru'] = [
                round($getParameterTampakBaruR1/($getParameterTampakBaruR1+$getParameterTidakTampakBaruR1+0.00000000001)*100,2),
                round($getParameterTampakBaruR2/($getParameterTampakBaruR2+$getParameterTidakTampakBaruR2+0.00000000001)*100,2),
                round($getParameterTampakBaruR3/($getParameterTampakBaruR3+$getParameterTidakTampakBaruR3+0.00000000001)*100,2),
                round($getParameterTampakBaruR4/($getParameterTampakBaruR4+$getParameterTidakTampakBaruR4+0.00000000001)*100,2),
            ];
            $data['parameter_ramah_lingkungan'] = [
                round($getParameterRamahLingkunganR1/($getParameterRamahLingkunganR1+$getParameterTidakRamahLingkunganR1+0.00000000001)*100,2),
                round($getParameterRamahLingkunganR2/($getParameterRamahLingkunganR2+$getParameterTidakRamahLingkunganR2+0.00000000001)*100,2),
                round($getParameterRamahLingkunganR3/($getParameterRamahLingkunganR3+$getParameterTidakRamahLingkunganR3+0.00000000001)*100,2),
                round($getParameterRamahLingkunganR4/($getParameterRamahLingkunganR4+$getParameterTidakRamahLingkunganR4+0.00000000001)*100,2),
            ];

        
        return response()->json($data)->setStatusCode(200);
    }

    // checker 
    public function checkerPekerjaanPerbaikanDashboard() {
        // Rekapitulasi Nilai Checker 

        if (date('d') < 8) {
            $DayDate = date('d');
            $MonthDate = idate('m') - 1;
            $YearDate = idate('Y');
        } else {
            $DayDate = date('d');
            $MonthDate = idate('m');
            $YearDate = idate('Y');
        }
        $getPekerjaanCheckerAll = Dashmo::getPekerjaanAllPie($MonthDate, $YearDate,'B');

        // Progres Nilai Checker
        if ($getPekerjaanCheckerAll > 0) {
            //get nilai checker R1
            $pekerjaanR1 = [
                Dashmo::getPekerjaanPie($MonthDate, $YearDate,'1', 'B', 'Selesai'),
                Dashmo::getPekerjaanPie($MonthDate, $YearDate,'1', 'B', 'Belum Dikerjakan')
            ];
            $pekerjaanR2 = [
                Dashmo::getPekerjaanPie($MonthDate, $YearDate,'2', 'B', 'Selesai'),
                Dashmo::getPekerjaanPie($MonthDate, $YearDate,'2', 'B', 'Belum Dikerjakan')
            ];
            $pekerjaanR3 = [
                Dashmo::getPekerjaanPie($MonthDate, $YearDate,'3', 'B', 'Selesai'),
                Dashmo::getPekerjaanPie($MonthDate, $YearDate,'3', 'B', 'Belum Dikerjakan')
            ];
            $pekerjaanR4 = [
                Dashmo::getPekerjaanPie($MonthDate, $YearDate,'4', 'B', 'Selesai'),
                Dashmo::getPekerjaanPie($MonthDate, $YearDate,'4', 'B', 'Belum Dikerjakan')
            ];
            $data['pekerjaanR1'] = $pekerjaanR1;
            $data['pekerjaanR2'] = $pekerjaanR2;
            $data['pekerjaanR3'] = $pekerjaanR3;
            $data['pekerjaanR4'] = $pekerjaanR4;
        }

        return response()->json($data)->setStatusCode(200);
    }
    // checker 
    public function checkerPekerjaanPergantianDashboard()
    {
        // Rekapitulasi Nilai Checker 

        if (date('d') < 8) {
            $DayDate = date('d');
            $MonthDate = idate('m') - 1;
            $YearDate = idate('Y');
        } else {
            $DayDate = date('d');
            $MonthDate = idate('m');
            $YearDate = idate('Y');
        }
        $getPekerjaanCheckerAll = Dashmo::getPekerjaanAllPie($MonthDate, $YearDate, 'C');

        // Progres Nilai Checker
        if ($getPekerjaanCheckerAll > 0) {
            //get nilai checker R1
            $pekerjaanR1 = [
                Dashmo::getPekerjaanPie($MonthDate, $YearDate, '1', 'C', 'Selesai'),
                Dashmo::getPekerjaanPie($MonthDate, $YearDate, '1', 'C', 'Belum Dikerjakan')
            ];
            $pekerjaanR2 = [
                Dashmo::getPekerjaanPie($MonthDate, $YearDate, '2', 'C', 'Selesai'),
                Dashmo::getPekerjaanPie($MonthDate, $YearDate, '2', 'C', 'Belum Dikerjakan')
            ];
            $pekerjaanR3 = [
                Dashmo::getPekerjaanPie($MonthDate, $YearDate, '3', 'C', 'Selesai'),
                Dashmo::getPekerjaanPie($MonthDate, $YearDate, '3', 'C', 'Belum Dikerjakan')
            ];
            $pekerjaanR4 = [
                Dashmo::getPekerjaanPie($MonthDate, $YearDate, '4', 'C', 'Selesai'),
                Dashmo::getPekerjaanPie($MonthDate, $YearDate, '4', 'C', 'Belum Dikerjakan')
            ];
            $data['pekerjaanR1'] = $pekerjaanR1;
            $data['pekerjaanR2'] = $pekerjaanR2;
            $data['pekerjaanR3'] = $pekerjaanR3;
            $data['pekerjaanR4'] = $pekerjaanR4;
        }

        return response()->json($data)->setStatusCode(200);
    }
}
