<?php

namespace App\Models\Admin\Checker\Laporan;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\Auth;

class AllRondeModel extends BaseModel
{


    // get data with pagination
    public static function getAllSearch($query_all, $month, $year) {
        if(empty($query_all)){
            return DB::table('branch_assessment as a')
            ->select('a.id','a.created_date','b.region_name','b.name as branch_name','c.name as round_name')
            ->join('master_branch as b','a.branch_id','=','b.id')
            ->join('master_round as c','a.round_id','=','c.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.status','selesai')
            ->where('a.data_status', '1')
            ->whereMonth('a.created_date', $month)
            ->whereYear('a.created_date', $year)
            ->orderByDesc('a.id')
            ->paginate(10)
            ->withQueryString();
        }
        else {

            return DB::table('branch_assessment as a')
                ->select('a.id','a.created_date','b.region_name','b.name as branch_name','c.name as round_name')
                ->join('master_branch as b','a.branch_id','=','b.id')
                ->join('master_round as c','a.round_id','=','c.id')
                ->where('a.status','selesai')
                ->where('a.branch_id', Auth::user()->branch_id)
                ->where('a.data_status', '1')
                ->whereMonth('a.created_date', $month)
                ->whereYear('a.created_date', $year)
                ->orWhere('b.name','LIKE', '%'.$query_all.'%')
                ->orWhere('b.region_name','LIKE', '%'.$query_all.'%')
                ->orWhere('c.name','LIKE', '%'.$query_all.'%')
                ->orderByDesc('a.created_date')
                ->paginate(10)
                ->withQueryString();
        }
    }

    // get by id
    public static function getById($id) {
        return DB::table('branch_assessment as a')
            ->select('a.*','b.region_name','b.name as branch_name','c.name as round_name')
            ->join('master_branch as b','a.branch_id','=','b.id')
            ->join('master_round as c','a.round_id','=','c.id')
            ->where('a.id',$id)
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.status','selesai')
            ->where('a.data_status', '1')
            ->orderByDesc('a.created_date')
            ->first();
    }

    // LIST TAHUN 
    public static function getListYear() {
        return DB::table('branch_assessment')->select(DB::raw('YEAR(created_date) AS name'))->orderBy('name','asc')->distinct()->get();
    }

    //get semua item
    public static function getAllItem($branch_assessment_id,$round_id)
    {
        return DB::table('branch_assessment_detail as a')
        ->select('a.branch_items_id','g.name as area', 'f.name as sub_area','d.name as item','c.unique_id','h.name as lokasi','i.name as komponen','a.score as nilai','a.img_name as nama_gambar','a.parameter','a.description as keterangan')
        ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
        ->join('branch_items as c','a.branch_items_id','=','c.id')
        ->join('master_items as d','c.items_id','=','d.id')
        ->join('master_sub_area as f','c.sub_area_id','=','f.id')
        ->join('master_area as g','f.area_id','=','g.id')
        ->join('master_location as h','g.location_id','=','h.id')
        ->join('master_assessment_component as i', 'a.assessment_component_id','i.id')
        ->where('a.branch_assessment_id', $branch_assessment_id)
        ->where('b.branch_id', Auth::user()->branch_id)
        // ->whereMonth('b.created_date',$bulan)
        ->where('g.round_id',$round_id)
        ->where('b.status','selesai')
        // ->groupBy('a.branch_items_id','area','sub_area','item','c.unique_id','lokasi','komponen','nilai')
        ->orderBy('area','asc')
        ->paginate(10);
    }
    // get daftar pembersihan/perbaikan/penggantian by branch assesment id
    public static function getListItemByBranchAssessmentId($branch_assessment_id, $score) {
        // get round items
        $rs_items = DB::table('branch_assessment_detail as a')
            ->select('a.branch_items_id','g.name as area', 'f.name as sub_area','d.name as item','c.unique_id')
            ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
            ->join('branch_items as c','a.branch_items_id','=','c.id')
            ->join('master_items as d','c.items_id','=','d.id')
            ->join('master_sub_area as f','c.sub_area_id','=','f.id')
            ->join('master_area as g','f.area_id','=','g.id')
            ->where('a.branch_assessment_id', $branch_assessment_id)
            ->where('a.score',$score)
            ->where('b.branch_id', Auth::user()->branch_id)
            ->where('b.status','selesai')
            ->groupBy('a.branch_items_id','area','sub_area','item','c.unique_id')
            ->orderBy('area','asc')
            ->get();

        // get round item komponen
        $rs_items->each(function ($item, $key) use($branch_assessment_id,$score) {
            $item->rs_komponen = DB::table('branch_assessment_detail as a')
                                ->select('a.branch_items_id','c.name','a.parameter','a.description','a.img_name')
                                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                                ->join('master_assessment_component as c','a.assessment_component_id','=','c.id')
                                ->where('a.branch_assessment_id', $branch_assessment_id)
                                ->where('a.branch_items_id', $item->branch_items_id)
                                ->where('a.score',$score)
                                ->where('b.branch_id', Auth::user()->branch_id)
                                ->where('b.status','selesai')
                                ->orderBy('c.id','asc')
                                ->get();

            // return
            return $item;
        });

        return $rs_items;
    }

    // get total komponen penilaian by round
    public static function getTotalKomponenByBranchAssessmentId($branch_assessment_id) {
        return DB::table('branch_assessment_detail')->where('branch_assessment_id', $branch_assessment_id)->count('assessment_component_id');
    }

    // get total komponen pembersihan by round
    public static function getTotalKomponenScoreByBranchAssessmentId($branch_assessment_id, $score) {
        return DB::table('branch_assessment_detail')->where('branch_assessment_id', $branch_assessment_id)->where('score', $score)->count('assessment_component_id');
    }

    // get total komponen parameter by round
    public static function getTotalKomponenParameterByBranchAssessmentId($branch_assessment_id, $parameter) {
        return DB::table('branch_assessment_detail')->where('branch_assessment_id', $branch_assessment_id)->where('parameter', $parameter)->count('parameter');
    }

    // get list area by round
    public static function getABCTiapAreaByBranchAssessmentId($branch_assessment_id) {
        $rs_data = DB::table('branch_assessment_detail as a')
                ->select('e.name',
                    DB::raw('COUNT(a.assessment_component_id) as jumlah_komponen'),
                    DB::raw('COUNT(CASE WHEN a.score = "A" THEN 1 ELSE NULL END) as jumlah_pembersihan'),
                    DB::raw('COUNT(CASE WHEN a.score = "B" THEN 1 ELSE NULL END) as jumlah_perbaikan'),
                    DB::raw('COUNT(CASE WHEN a.score = "C" THEN 1 ELSE NULL END) as jumlah_penggantian')
                )
                ->join('branch_items as c','a.branch_items_id','=','c.id')
                ->join('master_sub_area as d','c.sub_area_id','=','d.id')
                ->join('master_area as e','d.area_id','=','e.id')
                ->where('a.branch_assessment_id',$branch_assessment_id)
                ->groupBy('e.name')
                ->orderBy('e.name','asc')
                ->get();
        
        $rs_final = $rs_data->each(function($item, $key){
            if($item->jumlah_komponen > 0) {
                $item->persen_pembersihan = ($item->jumlah_pembersihan/$item->jumlah_komponen)*100;
                $item->persen_perbaikan = ($item->jumlah_perbaikan/$item->jumlah_komponen)*100;
                $item->persen_penggantian = ($item->jumlah_penggantian/$item->jumlah_komponen)*100;
            }
            else {
                $item->persen_pembersihan = 0;
                $item->persen_perbaikan = 0;
                $item->persen_penggantian = 0;
            }

            return $item;
        });

        return $rs_final;
    }

}
