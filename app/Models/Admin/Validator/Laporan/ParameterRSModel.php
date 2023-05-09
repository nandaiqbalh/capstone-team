<?php

namespace App\Models\Admin\Validator\Laporan;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class ParameterRSModel extends BaseModel
{

    // get data
    public static function getDataSearch($query_all, $month, $year) {
        // rs branch
        $rs_branch = ParameterRSModel::getListBranch($query_all);
        
        $rs_branch->each(function($value, $key) use($month, $year){
            // parameter
            $total_aman = ParameterRSModel::getTotalKomponenParameterBy($value->id,$month, $year,'Aman');
            $total_bersih = ParameterRSModel::getTotalKomponenParameterBy($value->id,$month, $year,'Bersih');
            $total_rapih = ParameterRSModel::getTotalKomponenParameterBy($value->id,$month, $year,'Rapih');
            $total_tampak_baru = ParameterRSModel::getTotalKomponenParameterBy($value->id,$month, $year,'Tampak Baru');
            $total_ramah_lingkungan = ParameterRSModel::getTotalKomponenParameterBy($value->id,$month, $year,'Ramah Lingkungan');
            $total_tidak_aman = ParameterRSModel::getTotalKomponenParameterBy($value->id,$month, $year,'Tidak Aman');
            $total_tidak_bersih = ParameterRSModel::getTotalKomponenParameterBy($value->id,$month, $year,'Tidak Bersih');
            $total_tidak_rapih = ParameterRSModel::getTotalKomponenParameterBy($value->id,$month, $year,'Tidak Rapih');
            $total_tidak_tampak_baru = ParameterRSModel::getTotalKomponenParameterBy($value->id,$month, $year,'Tidak Tampak Baru');
            $total_tidak_ramah_lingkungan = ParameterRSModel::getTotalKomponenParameterBy($value->id,$month, $year,'Tidak Ramah Lingkungan');
    
          
            $value->persen_aman = ($total_aman == 0) ? 0 : ($total_aman/($total_aman+$total_tidak_aman))*100;
            $value->persen_bersih = ($total_bersih == 0) ? 0 : ($total_bersih/($total_bersih+$total_tidak_bersih))*100;
            $value->persen_rapih = ($total_rapih == 0) ? 0 : ($total_rapih/($total_rapih+$total_tidak_rapih))*100;
            $value->persen_tampak_baru = ($total_tampak_baru == 0) ? 0 : ($total_tampak_baru/($total_tampak_baru+$total_tidak_tampak_baru))*100;
            $value->persen_ramah_lingkungan = ($total_ramah_lingkungan == 0) ? 0 : ($total_ramah_lingkungan/($total_ramah_lingkungan+$total_tidak_ramah_lingkungan))*100;

            return $value;

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

    // get total komponen parameter
    public static function getTotalKomponenParameterBy($branch_id, $month, $year, $parameter) {
        return DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id', $branch_id)
                ->whereMonth('b.created_date', $month)
                ->whereYear('b.created_date', $year)
                ->where('a.parameter', $parameter)
                ->where('b.data_status','1')
                ->where('b.status', 'selesai')
                ->count('a.parameter');
    }

    // LIST TAHUN 
    public static function getListYear() {
        return DB::table('branch_assessment')->select(DB::raw('YEAR(created_date) AS name'))->orderBy('name','asc')->distinct()->get();
    }
    
}
