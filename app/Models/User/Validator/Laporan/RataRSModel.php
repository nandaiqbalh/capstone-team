<?php

namespace App\Models\Admin\Validator\Laporan;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class RataRSModel extends BaseModel
{
    // get data pagination
    public static function getData() {
        // list branch
        $rs_branch = RekapitulasiNilaiModel::getListBranch('');
        // list bulan
        $rs_bulan = parent::bulanIndo();

        // perhitungan
        $rs_branch->each(function($item, $key) use($rs_bulan){
            
            // total komponen
            $rs_total = RekapitulasiNilaiModel::getTotalKomponenSetiapBulanBy($item->id, date('Y'));
            // total scor A B C
            $score_a = RekapitulasiNilaiModel::getTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'A');
            $score_b = RekapitulasiNilaiModel::getTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'B');
            $score_c = RekapitulasiNilaiModel::getTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'C');
            // dd($rs_total);
            // tambah key dan nilai persen abrt-rl
            foreach ($rs_bulan as $index => $value) {
                // jika $rs_total[$index] > 0
                if($rs_total[$index] > 0) {
                    // total nilai dalam sebulan
                    $total_nilai = (($score_a[$index]*1)+($score_b[$index]*0.5)+($score_c[$index]*0.25))/$rs_total[$index];
                    // rata-rata nilai dalam sebulan (dibagi 4 ronde)
                    $nilai_rata_bulanan = $total_nilai/4;
                    // assign to value
                    $item->{$value} = $nilai_rata_bulanan*100;
                }
                else {
                    // jika $rs_total[$index] == 0
                    $item->{$value} = 0;
                }
            }

            // return new item
            return $item;
        });
        
        return $rs_branch;
    }

    // get data pagination
    public static function getDataSearch($query_all, $year) {
        // list branch
        $rs_branch = RekapitulasiNilaiModel::getListBranch($query_all);
        // list bulan
        $rs_bulan = parent::bulanIndo();

        // perhitungan
        $rs_branch->each(function($item, $key) use($rs_bulan,$year){
            
            // total komponen
            $rs_total = RekapitulasiNilaiModel::getTotalKomponenSetiapBulanBy($item->id, $year);
            // total scor A B C
            $score_a = RekapitulasiNilaiModel::getTotalKomponenScoreSetiapBulanBy($item->id, $year,'A');
            $score_b = RekapitulasiNilaiModel::getTotalKomponenScoreSetiapBulanBy($item->id, $year,'B');
            $score_c = RekapitulasiNilaiModel::getTotalKomponenScoreSetiapBulanBy($item->id, $year,'C');

            // tambah key dan nilai persen abrt-rl
            foreach ($rs_bulan as $index => $value) {
                // jika $rs_total[$index]
                if($rs_total[$index] > 0) {
                    $item->{$value} = ((($score_a[$index]*1)+($score_b[$index]*0.5)+($score_c[$index]*0.25))/$rs_total[$index])*100;
                }
                else {
                    // jika $rs_total[$index] == 0
                    $item->{$value} = 0;
                }
            }

            // return new item
            return $item;
        });
        
        return $rs_branch;
    }

    // get list branch
    public static function getListBranch($name) {
        return DB::table('master_branch as a')
            ->select('a.id','a.name as branch_name')
            ->where('a.data_status','1')
            ->where('a.name','LIKE','%'.$name.'%')
            ->orderBy('a.name','asc')
            ->get();
    }

    // get total komponen penilaian by branch, month, year
    public static function getTotalKomponenSetiapBulanBy($branch_id,$year) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$key] = DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id',$branch_id)
                ->whereMonth('b.created_date', $key)
                ->whereYear('b.created_date', $year)
                ->where('b.data_status', '1')
                ->count('a.assessment_component_id');
        }

        return $data;
    }

    // get total komponen pembersihan by branch, month, year
    public static function getTotalKomponenScoreSetiapBulanBy($branch_id,$year, $score) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$key] = DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id',$branch_id)
                ->whereMonth('b.created_date', $key)
                ->whereYear('b.created_date', $year)
                ->where('b.data_status', '1')
                ->where('a.score', $score)
                ->count('a.assessment_component_id');
        }

        return $data;
    }

    // LIST TAHUN 
    public static function getListYear() {
        return DB::table('branch_assessment')->select(DB::raw('YEAR(created_date) AS name'))->orderBy('name','asc')->distinct()->get();
    }
}
