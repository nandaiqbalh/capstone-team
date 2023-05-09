<?php

namespace App\Models\Admin\Checker\Laporan;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\Auth;

class LaporanPekerjaanCheckerModel extends BaseModel
{
    // get data with pagination
    public static function getAllSearch($region_name, $branch_id, $round_id, $month, $year) {

        // default
        return DB::table('branch_assignment as a')
            ->select('a.id','a.created_date','b.region_name','b.name as branch_name','c.name as round_name','b.class')
            ->join('master_branch as b','a.branch_id','=','b.id')
            ->join('master_round as c','a.round_id','=','c.id')
            ->where('a.status','selesai')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status', '1')
            ->where('a.round_id', 'LIKE', "%".$round_id."%")
            ->whereMonth('a.created_date', $month)
            ->whereYear('a.created_date', $year)
            ->orderByDesc('a.created_date')
            ->paginate(10)
            ->withQueryString();
    }

    // get by id
    public static function getById($id) {
        return DB::table('branch_assignment as a')
            ->select('a.*','b.region_name','b.name as branch_name','c.name as round_name')
            ->join('master_branch as b','a.branch_id','=','b.id')
            ->join('master_round as c','a.round_id','=','c.id')
            ->where('a.id',$id)
            ->where('a.status','selesai')
            ->where('a.data_status', '1')
            ->orderByDesc('a.id')
            ->first();
    }

    // LIST TAHUN 
    public static function getListYear() {
        return DB::table('branch_assignment')->select(DB::raw('YEAR(created_date) AS name'))->orderBy('name','asc')->distinct()->get();
    }

    // get target rata-rata nilai
    public static function getAppSupportBy($key) {
        return DB::table('app_supports')->where('key',$key)->value('value');
    }

    // get master rs
    public static function getMasterBranch() {
        return DB::table('master_branch')->select('id','name')->orderBy('name','asc')->get();
    }

    // master regional
    public static function getMasterRegional() {
        return DB::table('master_region')->select('id','name')->orderBy('name','asc')->get();
    }

     // get daftar pembersihan/perbaikan/penggantian by branch assesment id
     public static function getListItemByBranchAssignmentId($branch_assignment_id, $score) {
        // get round items
        $rs_items = DB::table('branch_assignment_detail as a')
            ->select('a.branch_items_id','g.name as area', 'f.name as sub_area','d.name as item','c.unique_id')
            ->join('branch_assignment as b','a.branch_assignment_id','=','b.id')
            ->join('branch_items as c','a.branch_items_id','=','c.id')
            ->join('master_items as d','c.items_id','=','d.id')
            ->join('master_sub_area as f','c.sub_area_id','=','f.id')
            ->join('master_area as g','f.area_id','=','g.id')
            ->join('branch_assessment_detail as h','a.assessment_detail_id','=','h.id')
            ->where('a.branch_assignment_id', $branch_assignment_id)
            ->where('h.score',$score)
            ->where('b.status','selesai')
            ->groupBy('a.branch_items_id','area','sub_area','item','c.unique_id')
            ->orderBy('area','asc')
            ->get();

        // get round item komponen
        $rs_items->each(function ($item, $key) use($branch_assignment_id,$score) {
            $item->rs_komponen = DB::table('branch_assignment_detail as a')
                                ->select('a.branch_items_id','c.name','h.parameter','a.description','h.img_name as img_name_before','a.img_name as img_name_after')
                                ->join('branch_assignment as b','a.branch_assignment_id','=','b.id')
                                ->join('master_assessment_component as c','a.assessment_component_id','=','c.id')
                                ->join('branch_assessment_detail as h','a.assessment_detail_id','=','h.id')
                                ->where('a.branch_assignment_id', $branch_assignment_id)
                                ->where('a.branch_items_id', $item->branch_items_id)
                                ->where('h.score',$score)
                                ->where('b.status','selesai')
                                ->orderBy('c.id','asc')
                                ->get();

            // return
            return $item;
        });

        return $rs_items;
    }

    // get daftar belum dikerjakan
    public static function getListItemByBranchAssignmentStatus($branch_assignment_id, $assignment_status) {
        // get round items
        $rs_items = DB::table('branch_assignment_detail as a')
            ->select('a.branch_items_id','g.name as area', 'f.name as sub_area','d.name as item','c.unique_id')
            ->join('branch_assignment as b','a.branch_assignment_id','=','b.id')
            ->join('branch_items as c','a.branch_items_id','=','c.id')
            ->join('master_items as d','c.items_id','=','d.id')
            ->join('master_sub_area as f','c.sub_area_id','=','f.id')
            ->join('master_area as g','f.area_id','=','g.id')
            ->join('branch_assessment_detail as h','a.assessment_detail_id','=','h.id')
            ->where('a.branch_assignment_id', $branch_assignment_id)
            ->where('a.status',$assignment_status)
            ->where('b.status','selesai')
            ->groupBy('a.branch_items_id','area','sub_area','item','c.unique_id')
            ->orderBy('area','asc')
            ->get();

        // get round item komponen
        $rs_items->each(function ($item, $key) use($branch_assignment_id,$assignment_status) {
            $item->rs_komponen = DB::table('branch_assignment_detail as a')
                                ->select('a.branch_items_id','c.name','h.parameter','a.description','h.img_name as img_name_before','a.img_name as img_name_after')
                                ->join('branch_assignment as b','a.branch_assignment_id','=','b.id')
                                ->join('master_assessment_component as c','a.assessment_component_id','=','c.id')
                                ->join('branch_assessment_detail as h','a.assessment_detail_id','=','h.id')
                                ->where('a.branch_assignment_id', $branch_assignment_id)
                                ->where('a.branch_items_id', $item->branch_items_id)
                                ->where('a.status',$assignment_status)
                                ->where('b.status','selesai')
                                ->orderBy('c.id','asc')
                                ->get();

            // return
            return $item;
        });

        return $rs_items;
    }

    // get total komponen penilaian by round
    public static function getTotalKomponenByBranchAssignmentId($branch_assignment_id) {
        return DB::table('branch_assignment_detail as a')
                ->join('branch_assessment_detail as b','a.assessment_detail_id','=','b.id')
                ->where('a.branch_assignment_id', $branch_assignment_id)
                ->count('a.assessment_component_id');
    }

    // get total komponen pembersihan by round
    public static function getTotalKomponenScoreByBranchAssignmentId($branch_assignment_id, $score) {
        return DB::table('branch_assignment_detail as a')
                ->join('branch_assessment_detail as b','a.assessment_detail_id','=','b.id')
                ->where('a.branch_assignment_id', $branch_assignment_id)
                ->where('b.score', $score)
                ->count('a.assessment_component_id');
    }

    // get total komponen pembersihan by round belum dikerjakan
    public static function getTotalKomponenByBranchAssignmentIdAndStatus($branch_assignment_id, $assignment_status) {
        return DB::table('branch_assignment_detail as a')
                ->join('branch_assessment_detail as b','a.assessment_detail_id','=','b.id')
                ->where('a.branch_assignment_id', $branch_assignment_id)
                ->where('a.status', $assignment_status)
                ->count('a.assessment_component_id');
    }

    // get list area by round
    // D = belum dikerjakan
    public static function getBCDTiapAreaByBranchAssignmentId($branch_assignment_id) {
        $rs_data = DB::table('branch_assignment_detail as a')
                ->select('e.name',
                    DB::raw('COUNT(a.assessment_component_id) as jumlah_komponen'),
                    DB::raw('COUNT(CASE WHEN h.score = "B" THEN 1 ELSE NULL END) as jumlah_perbaikan'),
                    DB::raw('COUNT(CASE WHEN h.score = "C" THEN 1 ELSE NULL END) as jumlah_penggantian'),
                    DB::raw('COUNT(CASE WHEN a.status = "Belum Dikerjakan" THEN 1 ELSE NULL END) as jumlah_belum_dikerjakan'),
                    DB::raw('COUNT(CASE WHEN a.status = "Selesai" THEN 1 ELSE NULL END) as jumlah_selesai')
                )
                ->join('branch_items as c','a.branch_items_id','=','c.id')
                ->join('master_sub_area as d','c.sub_area_id','=','d.id')
                ->join('master_area as e','d.area_id','=','e.id')
                ->join('branch_assessment_detail as h','a.assessment_detail_id','=','h.id')
                ->where('a.branch_assignment_id',$branch_assignment_id)
                ->groupBy('e.name')
                ->orderBy('e.name','asc')
                ->get();
        
        $rs_final = $rs_data->each(function($item, $key){
            if($item->jumlah_komponen > 0) {
                $item->persen_perbaikan     = ($item->jumlah_perbaikan/$item->jumlah_komponen)*100;
                $item->persen_penggantian   = ($item->jumlah_penggantian/$item->jumlah_komponen)*100;
                $item->persen_belum_dikerjakan = ($item->jumlah_belum_dikerjakan/$item->jumlah_komponen)*100;
                $item->persen_selesai = ($item->jumlah_selesai/$item->jumlah_komponen)*100;
            }
            else {
                $item->persen_perbaikan     = 0;
                $item->persen_penggantian   = 0;
                $item->persen_belum_dikerjakan = 0;
                $item->persen_selesai       = 0;
            }

            return $item;
        });

        return $rs_final;
    }

    public static function updateBranchAssignment($id, $params) {
        return DB::table('branch_assignment')->where('id', $id)->update($params);
    }

    // ---------------------------------------------------------------------------------------------------------

    // get list rs
    public static function getListRs($round_id, $month, $year){
        // cek round
        if($round_id == '0'){
            // semua ronde
            return DB::table('branch_assignment as a')
                    ->select('b.id','b.name')
                    ->join('master_branch as b', 'a.branch_id','=','b.id')
                    ->whereMonth('a.created_date', $month)
                    ->whereYear('a.created_date', $year)
                    ->where('b.type','rumah sakit')
                    ->orderBy('b.name','asc')
                    ->groupBy('b.id','b.name')
                    ->get();
        }
        else {
            // ronde tertentu
            return DB::table('branch_assignment as a')
                    ->select('b.id','b.name')
                    ->join('master_branch as b', 'a.branch_id','=','b.id')
                    ->where('a.round_id',$round_id)
                    ->whereMonth('a.created_date', $month)
                    ->whereYear('a.created_date', $year)
                    ->where('b.type','rumah sakit')
                    ->orderBy('b.name','asc')
                    ->get();

        }
    }

    // get list rs by regional
    public static function getListRsByRegional($region_name,$round_id, $month, $year){
        // cek round
        if($round_id == '0'){
            // semua ronde
            return DB::table('branch_assignment as a')
                    ->select('b.id','b.name')
                    ->join('master_branch as b', 'a.branch_id','=','b.id')
                    ->where('b.region_name', $region_name)
                    ->whereMonth('a.created_date', $month)
                    ->whereYear('a.created_date', $year)
                    ->where('b.type','rumah sakit')
                    ->orderBy('b.name','asc')
                    ->groupBy('b.id','b.name')
                    ->get();
        }
        else {
            // ronde tertentu
            return DB::table('branch_assignment as a')
                    ->select('b.id','b.name')
                    ->join('master_branch as b', 'a.branch_id','=','b.id')
                    ->where('b.region_name', $region_name)
                    ->where('a.round_id',$round_id)
                    ->whereMonth('a.created_date', $month)
                    ->whereYear('a.created_date', $year)
                    ->where('b.type','rumah sakit')
                    ->orderBy('b.name','asc')
                    ->get();

        }
    }

    // get branch assensmen 
    public static function getBranchAssignmentByParam($branch_id, $round_id, $month, $year){
        return DB::table('branch_assignment as a')
                ->select('a.*','b.region_name','b.name as branch_name','c.name as round_name')
                ->join('master_branch as b','a.branch_id','=','b.id')
                ->join('master_round as c','a.round_id','=','c.id')
                ->where('a.branch_id',$branch_id)
                ->where('a.round_id',$round_id)
                ->whereMonth('a.created_date', $month)
                ->whereYear('a.created_date', $year)
                ->where('a.status','=','Selesai')
                ->where('a.data_status', '1')
                ->first();
    }

    // get branch assensmen all round
    public static function getBranchAssignmentAllRoundByParam($branch_id,$month, $year){
        return DB::table('branch_assignment as a')
                ->select('a.*','b.region_name','b.name as branch_name','c.name as round_name')
                ->join('master_branch as b','a.branch_id','=','b.id')
                ->join('master_round as c','a.round_id','=','c.id')
                ->where('a.branch_id',$branch_id)
                ->whereMonth('a.created_date', $month)
                ->whereYear('a.created_date', $year)
                ->where('a.status','=','Selesai')
                ->where('a.data_status', '1')
                ->get();
    }

    // -----------------------------------------------------------------------------------------------------------
    // get total komponen pembersihan by round
    public static function getTotalKomponenScoreByBranchAssessmentId($branch_assessment_id, $score) {
        return DB::table('branch_assessment_detail')->where('branch_assessment_id', $branch_assessment_id)->where('score', $score)->count('assessment_component_id');
    }
    
    // get total
    public static function getTotalKomponenByBranchAssessmentId($branch_assessment_id) {
        return DB::table('branch_assessment_detail')->where('branch_assessment_id', $branch_assessment_id)->count('assessment_component_id');
    }

    public static function getTotalKomponenByBranchAssessmentByArrRsId($arr_branch_id, $round_id, $month, $year, $score) {
        if($round_id == '0'){
            return DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->whereIn('b.branch_id', $arr_branch_id)
                ->whereMonth('b.created_date',$month)
                ->whereYear('b.created_date',$year)
                ->where('a.score', $score)
                ->count('a.assessment_component_id');
        }
        else {
            return DB::table('branch_assessment_detail as a')
                    ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                    ->whereIn('b.branch_id', $arr_branch_id)
                    ->where('b.round_id', $round_id)
                    ->whereMonth('b.created_date',$month)
                    ->whereYear('b.created_date',$year)
                    ->where('a.score', $score)
                    ->count('a.assessment_component_id');
        }
    }
}
