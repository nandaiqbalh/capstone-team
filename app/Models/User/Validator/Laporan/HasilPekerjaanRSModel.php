<?php

namespace App\Models\Admin\Validator\Laporan;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class HasilPekerjaanRSModel extends BaseModel
{
    // get data perbaikan
    public static function getDataPerbaikan($query_all, $year) {
        // list branch
        $rs_branch = HasilPekerjaanRSModel::getListBranch($query_all);
        // list bulan
        $rs_bulan = parent::bulanIndo();

        $rs_branch->getCollection()->transform(function ($item)  use($rs_bulan,$year) {
            // total komponen
            $rs_total_komponen = HasilPekerjaanRSModel::getTotalKomponenSetiapBulanBy($item->id, $year);
            // total scor B
            $rs_total_perbaikan = HasilPekerjaanRSModel::getTotalKomponenScoreSetiapBulanBy($item->id, $year,'B');

            // tambah key dan nilai persen abrt-rl
            foreach ($rs_bulan as $index => $value) {
                // jika $rs_total[$index]
                if($rs_total_komponen[$index] > 0) {
                    $item->{$value} = ($rs_total_perbaikan[$index]/$rs_total_komponen[$index])*100;
                }
                else {
                    // jika $rs_total[$index] == 0
                    $item->{$value} = 0;
                }

                // ---------------------------------------------
                $pekerjaan = HasilPekerjaanRSModel::totalPekerjaanTiapBulanByTahun($item->id, $index,$year,'B');
                // hitung presentase
                if($pekerjaan->jumlah_total != 0){
                    $presentase_selesai             =  round(($pekerjaan->jumlah_selesai/$pekerjaan->jumlah_total)*100,2);
                    $presentase_belum_dikerjakan    =  round(($pekerjaan->jumlah_belum_dikerjakan/$pekerjaan->jumlah_total)*100,2);
                }
                else {
                    $presentase_selesai             = 0;
                    $presentase_belum_dikerjakan    = 0;
                }

                $item->{'jumlah_total_'.$value}            = $pekerjaan->jumlah_total;
                $item->{'persen_selesai_'.$value}          = $presentase_selesai;
                $item->{'persen_belum_dikerjakan_'.$value} = $presentase_belum_dikerjakan;
                $item->{'tooltip_title_'.$value}           = "<div class='text-left'>
                                                                    <p>
                                                                        Total Komponen
                                                                        <br>
                                                                        ".$pekerjaan->jumlah_total."
                                                                    </p>
                                                                    <p>
                                                                        Jumlah Selesai<br>
                                                                        ".$pekerjaan->jumlah_selesai." 
                                                                    </p>
                                                                    <p>
                                                                        Jumlah Belum Dikerjakan <br> 
                                                                        ".$pekerjaan->jumlah_belum_dikerjakan."
                                                                    </p>
                                                                </div>";
            }

            // return new item
            return $item;
        });

        return $rs_branch;
    }

    // get data perbaikan
    public static function getDataPenggantian($query_all, $year) {
        // list branch
        $rs_branch = HasilPekerjaanRSModel::getListBranch($query_all);
        // list bulan
        $rs_bulan = parent::bulanIndo();

        $rs_branch->getCollection()->transform(function ($item)  use($rs_bulan,$year) {
            // total komponen
            $rs_total_komponen = HasilPekerjaanRSModel::getTotalKomponenSetiapBulanBy($item->id, $year);
            // total scor C
            $rs_total_penggantian = HasilPekerjaanRSModel::getTotalKomponenScoreSetiapBulanBy($item->id, $year,'C');
            
            // tambah key dan nilai persen abrt-rl
            foreach ($rs_bulan as $index => $value) {
                // jika $rs_total[$index]
                if($rs_total_komponen[$index] > 0) {
                    $item->{$value} = ($rs_total_penggantian[$index]/$rs_total_komponen[$index])*100;
                }
                else {
                    // jika $rs_total[$index] == 0
                    $item->{$value} = 0;
                }

                // ------------------------------------------------------
                $pekerjaan = HasilPekerjaanRSModel::totalPekerjaanTiapBulanByTahun($item->id, $index,$year,'C');
                // hitung presentase
                if($pekerjaan->jumlah_total != 0){
                    $presentase_selesai             =  round(($pekerjaan->jumlah_selesai/$pekerjaan->jumlah_total)*100,2);
                    $presentase_belum_dikerjakan    =  round(($pekerjaan->jumlah_belum_dikerjakan/$pekerjaan->jumlah_total)*100,2);
                }
                else {
                    $presentase_selesai             = 0;
                    $presentase_belum_dikerjakan    = 0;
                }

                $item->{'jumlah_total_'.$value}            = $pekerjaan->jumlah_total;
                $item->{'persen_selesai_'.$value}          = $presentase_selesai;
                $item->{'persen_belum_dikerjakan_'.$value} = $presentase_belum_dikerjakan;
                $item->{'tooltip_title_'.$value}           = "<div class='text-left'>
                                                                    <p>
                                                                        Total Komponen
                                                                        <br>
                                                                        ".$pekerjaan->jumlah_total."
                                                                    </p>
                                                                    <p>
                                                                        Jumlah Selesai<br>
                                                                        ".$pekerjaan->jumlah_selesai." 
                                                                    </p>
                                                                    <p>
                                                                        Jumlah Belum Dikerjakan <br> 
                                                                        ".$pekerjaan->jumlah_belum_dikerjakan."
                                                                    </p>
                                                                </div>";
            }

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
            ->paginate(10)
            ->withQueryString();
    }

    // get total komponen penilaian by branch, month, year
    public static function getTotalKomponenSetiapBulanBy($branch_id,$year) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$key] = DB::table('branch_assignment_detail as a')
                ->join('branch_assignment as b','a.branch_assignment_id','=','b.id')
                ->where('b.branch_id',$branch_id)
                ->whereMonth('b.created_date', $key)
                ->whereYear('b.created_date', $year)
                ->where('b.data_status', '1')
                ->where('b.status','selesai')
                ->count('a.assessment_component_id');
        }

        return $data;
    }

    // get total komponen by branch, month, year
    public static function getTotalKomponenScoreSetiapBulanBy($branch_id,$year, $score) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$key] = DB::table('branch_assignment_detail as a')
                ->join('branch_assignment as b','a.branch_assignment_id','=','b.id')
                ->join('branch_assessment_detail as c','a.assessment_detail_id','=','c.id')
                ->where('b.branch_id',$branch_id)
                ->whereMonth('b.created_date', $key)
                ->whereYear('b.created_date', $year)
                ->where('b.data_status', '1')
                ->where('b.status', 'selesai')
                ->where('c.score', $score)
                ->count('a.assessment_component_id');
        }

        return $data;
    }

    // LIST TAHUN 
    public static function getListYear() {
        return DB::table('branch_assignment')->select(DB::raw('YEAR(created_date) AS name'))->orderBy('name','asc')->distinct()->get();
    }

    public static function totalPekerjaanTiapBulanByTahun($branch_id, $month, $year, $score){
        return DB::table('branch_assignment_detail as a')
                ->select(
                    DB::raw('COUNT(a.id) as jumlah_total'),
                    DB::raw('COUNT(CASE WHEN a.status = "Belum Dikerjakan" THEN 1 ELSE NULL END) as jumlah_belum_dikerjakan'),
                    DB::raw('COUNT(CASE WHEN a.status = "Selesai" THEN 1 ELSE NULL END) as jumlah_selesai')
                )
                ->join('branch_assignment as b','a.branch_assignment_id','=','b.id')
                ->join('branch_assessment_detail as c','a.assessment_detail_id','=','c.id')
                ->where('b.branch_id', $branch_id)
                ->whereMonth('b.created_date', $month)
                ->whereYear('b.created_date', $year)
                ->where('c.score', $score)
                ->first();
    }
}
