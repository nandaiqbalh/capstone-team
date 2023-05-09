<?php

namespace App\Models\Admin\Validator\Laporan;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;
// VERIVIKATOR TERLAMBAT PERSETUJUAN
class TerlambatPersetujuanModel extends BaseModel
{

    // get data
    public static function getDataSearch($query_all, $year) {
        // list ronde
        $rs_ronde = TerlambatPersetujuanModel::getListRonde($query_all);

        // perhitungan
        $rs_ronde->each(function($item, $key) use($year){
            
            $arr_terlambat_submit = TerlambatPersetujuanModel::getListTerlambatSubmitSemuaBulan($item->id, $year);
            foreach ($arr_terlambat_submit as $key => $value) {
                $item->{$key} = $value;
            }
            // return new item
            return $item;
        });
        
        return $rs_ronde;
    }

    // list ronde
    public static function getListRonde($name) {
        return DB::table('master_round')
            ->select('id','name as round_name')
            ->where('data_status','1')
            ->where('name','LIKE','%'.$name.'%')
            ->orderBy('name','asc')
            ->get();
    }

    // get round by id
    public static function getRoundById($round_id) {
        return DB::table('master_round')
            ->select('id','name')
            ->where('id', $round_id)
            ->where('data_status','1')
            ->first();
    }

    // get by id year
    public static function getDetail($round_id,$month, $year){
        return TerlambatPersetujuanModel::getDetailListTerlambatSubmitBy($round_id,$month, $year);
    }

    // get submit terlambat bulanan
    public static function getListTerlambatSubmitSemuaBulan($round_id, $year ) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$value] =  DB::table('branch_assessment as a')
                        ->select('b.name','b.class','b.region_name')
                        ->join('master_branch as b','a.branch_id','=','b.id')
                        ->where('a.round_id', $round_id)
                        ->whereNotNull('a.verifikator_2_approved_by_system')
                        ->whereMonth('a.created_date', $key)
                        ->whereYear('a.created_date', $year)
                        ->where('a.status','selesai')
                        ->where('a.data_status','1')
                        ->orderBy('b.name','asc')
                        ->count('a.verifikator_2_approved_by_system');
        }

        return $data;
    }

    // public static function getDetailListTerlambatSubmitSemuaBulan($round_id, $year ) {
    //     $rs_bulan = parent::bulanIndo();

    //     $data = [];
    //     foreach ($rs_bulan as $key => $value) {

    //         $rs_branch = DB::table('branch_assessment as a')
    //             ->select('b.name')
    //             ->join('master_branch as b','a.branch_id','=','b.id')
    //             ->where('a.round_id', $round_id)
    //             ->whereNotNull('a.verifikator_2_approved_by_system')
    //             ->whereMonth('a.created_date', $key)
    //             ->whereYear('a.created_date', $year)
    //             ->where('a.status','selesai')
    //             ->where('a.data_status','1')
    //             ->get();

    //         $data[$value] =  [
    //             'jumlah'    => $rs_branch->count(),
    //             'rs_branch' => $rs_branch
    //         ];
    //     }

    //     return $data;
    // }

    public static function getDetailListTerlambatSubmitBy($round_id,$month, $year ) {

        $data = DB::table('branch_assessment as a')
                ->select('b.name','b.class','b.region_name')
                ->join('master_branch as b','a.branch_id','=','b.id')
                ->where('a.round_id', $round_id)
                ->whereNotNull('a.verifikator_2_approved_by_system')
                ->whereMonth('a.created_date', $month)
                ->whereYear('a.created_date', $year)
                ->where('a.status','selesai')
                ->where('a.data_status','1')
                ->orderBy('b.name','asc')
                ->get();

        return $data;
    }
    

    // LIST TAHUN 
    public static function getListYear() {
        return DB::table('branch_assessment')->select(DB::raw('YEAR(created_date) AS name'))->orderBy('name','asc')->distinct()->get();
    }
}
