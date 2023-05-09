<?php

namespace App\Models\Admin\Checker\Pekerjaan;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PekerjaanModel extends BaseModel
{

    // get data Ronde
    public static function getDataRound($start_day) {
        return DB::table('master_round')
            ->where('start_day','<=',$start_day)
            ->where('end_day','>=',$start_day)
            ->first();
    }
    // get data Ronde
    public static function getDataRoundBranch($start_day,$month,$year, $branch_id) {
        return DB::table('branch_assessment as a')
            ->select('b.name as nama_ronde','b.id as id_ronde','a.id as branch_assessment_id','a.status as status','b.end_day as end_day','a.branch_id','c.name as branch_name')
            ->join('master_round as b','a.round_id','b.id')
            ->join('master_branch as c','a.branch_id','c.id')
            ->where('a.branch_id','=',$branch_id)
            ->where('b.start_day','<=',$start_day)
            ->where('b.end_day','>=',$start_day)
            ->whereMonth('a.created_date',$month)
            ->whereYear('a.created_date',$year)
            ->first();
    }

    // getBranchAssignment
    public static function getBranchAssignment($branch_id,$round_assignment) {
        return DB::table('branch_assignment as a')
            ->select('a.*', 'b.name as branch_name')
            ->join('master_branch as b','a.branch_id','b.id')
            ->where('a.branch_id','=',$branch_id)
            ->where('a.round_id','=',$round_assignment)
            ->orderByDesc('id')
            ->first();
        }
    // // get data Ronde
    // public static function getPekerjaanRoundBranch($start_day,$month,$year, $branch_id) {
    //     return DB::table('branch_assignment as a')
    //         ->select('b.name as nama_ronde','b.id as id_ronde','a.id as branch_assessment_id','a.status as status','b.end_day as end_day','a.branch_id','c.name as branch_name')
    //         ->join('master_round as b','a.round_id','b.id')
    //         ->join('master_branch as c','a.branch_id','c.id')
    //         ->where('a.branch_id','=',$branch_id)
    //         ->where('b.start_day','<=',$start_day)
    //         ->where('b.end_day','>=',$start_day)
    //         ->whereMonth('a.created_date',$month)
    //         ->whereYear('a.created_date',$year)
    //         ->first();
    // }

    // get Email Verifikator 1
    public static function emailV1($branch_id) {
        return DB::table('app_user as a')
            ->select('a.*','c.role_name as position')
            ->join('app_role_user as b', 'a.user_id','b.user_id')
            ->join('app_role as c', 'b.role_id','c.role_id')
            ->where('a.branch_id', $branch_id)
            ->where('a.user_active','1')
            ->where('c.role_name','Verifikator 1')
            ->orderByDesc('a.user_id')
            ->first();
    }
    
    // get data komponen yang dikerjakan
    public static function getDataAssignment($branch_assignment_id,$round_id) {
        return DB::table('branch_assignment_detail as a')
            ->select('a.id as assignment_detail_id','i.description as keterangan_penilaian', 'i.parameter', 'i.score', 'i.img_name as img_before','a.status','c.unique_id','a.description as keterangan','d.name as nama_item','g.name as nama_lokasi','f.name as nama_area','e.name as nama_sub_area','h.name as nama_komponen','a.img_name as nama_gambar','a.revision_status as status_revisi','a.revision_description as revisi_deskripsi')
            ->join('branch_assignment as b','a.branch_assignment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->join('branch_assessment_detail as i', 'a.assessment_detail_id','i.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id',$round_id)
            ->where('c.data_status','1')
            ->where('h.data_status','1')
            ->where('a.branch_assignment_id',$branch_assignment_id)
            ->paginate(10);
    }

    // search
    public static function getDataAssignmentSearch($branch_assignment_id,$round_id,$search, $status) {
        if($status == '0'){
            return DB::table('branch_assignment_detail as a')
                ->select('a.id as assignment_detail_id','i.description as keterangan_penilaian', 'i.parameter', 'i.score', 'i.img_name as img_before','a.status','c.unique_id','a.description as keterangan','d.name as nama_item','g.name as nama_lokasi','f.name as nama_area','e.name as nama_sub_area','h.name as nama_komponen','a.img_name as nama_gambar','a.revision_status as status_revisi','a.revision_description as revisi_deskripsi')
                ->join('branch_assignment as b','a.branch_assignment_id','b.id')
                ->join('branch_items as c','a.branch_items_id','c.id')
                ->join('master_items as d', 'c.items_id','d.id')
                ->join('master_sub_area as e', 'c.sub_area_id','e.id')
                ->join('master_area as f', 'e.area_id','f.id')
                ->join('master_location as g', 'f.location_id','g.id')
                ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
                ->join('branch_assessment_detail as i', 'a.assessment_detail_id','i.id')
                ->where('c.branch_id', Auth::user()->branch_id)
                ->where('b.round_id',$round_id)
                ->where('h.name', 'LIKE', "%".$search."%")
                ->where('a.branch_assignment_id',$branch_assignment_id)
                ->where('c.data_status','1')
                ->where('h.data_status','1')
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('d.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('e.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('f.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('h.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->paginate(10);
        }
        elseif($status == 'Selesai' || $status == 'Belum Dikerjakan'){
            return DB::table('branch_assignment_detail as a')
                ->select('a.id as assignment_detail_id','i.description as keterangan_penilaian', 'i.parameter', 'i.score', 'i.img_name as img_before','a.status','c.unique_id','a.description as keterangan','d.name as nama_item','g.name as nama_lokasi','f.name as nama_area','e.name as nama_sub_area','h.name as nama_komponen','a.img_name as nama_gambar','a.revision_status as status_revisi','a.revision_description as revisi_deskripsi')
                ->join('branch_assignment as b','a.branch_assignment_id','b.id')
                ->join('branch_items as c','a.branch_items_id','c.id')
                ->join('master_items as d', 'c.items_id','d.id')
                ->join('master_sub_area as e', 'c.sub_area_id','e.id')
                ->join('master_area as f', 'e.area_id','f.id')
                ->join('master_location as g', 'f.location_id','g.id')
                ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
                ->join('branch_assessment_detail as i', 'a.assessment_detail_id','i.id')
                ->where('c.branch_id', Auth::user()->branch_id)
                ->where('b.round_id',$round_id)
                ->where('h.name', 'LIKE', "%".$search."%")
                ->where('a.branch_assignment_id',$branch_assignment_id)
                ->where('a.status', $status)
                ->where('c.data_status','1')
                ->where('h.data_status','1')
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('d.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                    ->where('a.status', $status)
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('e.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                    ->where('a.status', $status)
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('f.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                    ->where('a.status', $status)
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('h.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                    ->where('a.status', $status)
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->paginate(10);
        }
        elseif($status == '3'){
            return DB::table('branch_assignment_detail as a')
                ->select('a.id as assignment_detail_id','i.description as keterangan_penilaian', 'i.parameter', 'i.score', 'i.img_name as img_before','a.status','c.unique_id','a.description as keterangan','d.name as nama_item','g.name as nama_lokasi','f.name as nama_area','e.name as nama_sub_area','h.name as nama_komponen','a.img_name as nama_gambar','a.revision_status as status_revisi','a.revision_description as revisi_deskripsi')
                ->join('branch_assignment as b','a.branch_assignment_id','b.id')
                ->join('branch_items as c','a.branch_items_id','c.id')
                ->join('master_items as d', 'c.items_id','d.id')
                ->join('master_sub_area as e', 'c.sub_area_id','e.id')
                ->join('master_area as f', 'e.area_id','f.id')
                ->join('master_location as g', 'f.location_id','g.id')
                ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
                ->join('branch_assessment_detail as i', 'a.assessment_detail_id','i.id')
                ->where('c.branch_id', Auth::user()->branch_id)
                ->where('b.round_id',$round_id)
                ->where('h.name', 'LIKE', "%".$search."%")
                ->where('a.branch_assignment_id',$branch_assignment_id)
                ->where('a.status', 'Belum Dikerjakan')
                ->whereNull('a.description')
                ->where('c.data_status','1')
                ->where('h.data_status','1')
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('d.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                     ->where('a.status', 'Belum Dikerjakan')
                    ->whereNull('a.description')
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('e.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                     ->where('a.status', 'Belum Dikerjakan')
                    ->whereNull('a.description')
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('f.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                     ->where('a.status', 'Belum Dikerjakan')
                    ->whereNull('a.description')
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->orWhere(function($query) use($branch_assignment_id,$round_id,$search, $status) {
                    $query->where('c.branch_id', Auth::user()->branch_id)
                    ->where('b.round_id',$round_id)
                    ->where('h.name', 'LIKE', "%".$search."%")
                    ->where('a.branch_assignment_id',$branch_assignment_id)
                     ->where('a.status', 'Belum Dikerjakan')
                    ->whereNull('a.description')
                    ->where('c.data_status','1')
                    ->where('h.data_status','1');
                })
                ->paginate(10);
        }
        
    }
    
    
    // Komponen yang dikerjakan semua 
    public static function getDataAssignmentAll($branch_assignment_id,$round_id) {
        return DB::table('branch_assignment_detail as a')
            ->select('i.parameter','i.img_name as img_before','c.unique_id','a.description as keterangan','d.name as nama_item','g.name as nama_lokasi','f.name as nama_area','e.name as nama_sub_area','h.name as nama_komponen','a.img_name as nama_gambar','a.revision_status as status_revisi','a.revision_description as revisi_deskripsi')
            ->join('branch_assignment as b','a.branch_assignment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->join('branch_assessment_detail as i', 'a.assessment_detail_id','i.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id',$round_id)
            ->where('c.data_status','1')
            ->where('h.data_status','1')
            ->where('a.branch_assignment_id',$branch_assignment_id)
            ->orderByDesc('a.id')
            ->count();
    }
    

    // get Jumlah data komponen yang sudah dinilai
    public static function getDataAssignmentCount($branch_assignment_id,$round_id) {
        return DB::table('branch_assignment_detail as a')
            ->select('i.parameter','i.img_name as img_before','c.unique_id','a.description as keterangan','d.name as nama_item','g.name as nama_lokasi','f.name as nama_area','e.name as nama_sub_area','h.name as nama_komponen','a.img_name as nama_gambar','a.revision_status as status_revisi','a.revision_description as revisi_deskripsi')
            ->join('branch_assignment as b','a.branch_assignment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->join('branch_assessment_detail as i', 'a.assessment_detail_id','i.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id',$round_id)
            ->where('c.data_status','1')
            ->where('h.data_status','1')
            ->whereNot('a.description',null)
            ->where('a.branch_assignment_id',$branch_assignment_id)
            ->orderByDesc('a.id')
            ->count();
    }

    public static function countAssignmentBelumDikerjakanDenganKeterangan($branch_assignment_id,$round_id) {
        return DB::table('branch_assignment_detail as a')
            ->select('i.parameter','i.img_name as img_before','c.unique_id','a.description as keterangan','d.name as nama_item','g.name as nama_lokasi','f.name as nama_area','e.name as nama_sub_area','h.name as nama_komponen','a.img_name as nama_gambar','a.revision_status as status_revisi','a.revision_description as revisi_deskripsi')
            ->join('branch_assignment as b','a.branch_assignment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->join('branch_assessment_detail as i', 'a.assessment_detail_id','i.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id',$round_id)
            ->where('c.data_status','1')
            ->where('h.data_status','1')
            ->where('a.status','Belum Dikerjakan')
            ->whereNotNull('a.description')
            ->where('a.branch_assignment_id',$branch_assignment_id)
            ->orderByDesc('a.id')
            ->count();
    }

    // get data with pagination
    public static function getDataSubAreaByID($id) {
        return DB::table('branch_items as a')
            ->select('d.name as nama_sub_area','a.sub_area_id','e.name as nama_area')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('a.sub_area_id',$id)
            ->orderByDesc('a.id')
            ->get();
    }
    // get data with pagination
    public static function getDataWithPagination() {
        return DB::table('branch_items as a')
            ->select('a.id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->orderByDesc('a.id')
            ->paginate(10);
    }

    // get search
    public static function getDataSearch($branch_assessment_id,$search,$round_id) {
        return DB::table('branch_assessment_detail as a')
            ->select('c.unique_id','a.description as keterangan','d.name as nama_item','g.name as nama_lokasi','f.name as nama_area','e.name as nama_sub_area','h.name as nama_komponen','a.img_name as nama_gambar','a.revision_status as status_revisi','a.parameter as parameter','a.revision_description as revisi_deskripsi')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('c.data_status','1')
            ->where('b.round_id',$round_id)
            ->where('d.name', 'LIKE', "%".$search."%")
            ->orWhere('g.name', 'LIKE', "%".$search."%")
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('c.data_status','1')
            ->where('b.round_id',$round_id)
            ->orWhere('e.name', 'LIKE', "%".$search."%")
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('c.data_status','1')
            ->where('b.round_id',$round_id)
            ->orWhere('f.name', 'LIKE', "%".$search."%")
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('c.data_status','1')
            ->where('b.round_id',$round_id)
            ->orWhere('h.name', 'LIKE', "%".$search."%")
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('c.data_status','1')
            ->where('b.round_id',$round_id)
            ->orWhere('a.revision_status', 'LIKE', "%".$search."%")
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('c.data_status','1')
            ->where('b.round_id',$round_id)
            ->where('a.branch_assessment_id',$branch_assessment_id)
            ->orderByDesc('a.id')
            ->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($id) {
        return DB::table('branch_items as a')
            ->select('a.id','a.items_id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('a.id', $id)
            ->orderByDesc('a.id')
            ->first();
    }

    // get data Location Master
    public static function getDataLocation() {
        return DB::table('master_location')
        ->get();
    }
    
    // get data Area Master
    public static function getDataArea($location_id) {
        return DB::table('master_area')
        ->where('location_id',$location_id)
        ->get();
    }

    // get data Sub Area Master
    public static function getDataSubArea($area_id) {
        return DB::table('master_sub_area')
        ->where('area_id',$area_id)
        ->get();
    }
    
    // get data Item Master
    public static function getDataItem() {
        return DB::table('master_items')->get();
    }
    // get data Komponen
    public static function getDataComponent($item_id) {
        return DB::table('master_assessment_component')
        // ->where('items_id',$item_id)
        ->paginate(10);
    }

    // get data by email
    public static function getByEmail($email) {
        return DB::table('app_user')->where('user_email', $email)->first();
    }

    // get role untuk check
    public static function getByRole($role) {
        return DB::table('app_user as a')
            ->select('a.*','c.role_name as position')
            ->join('app_role_user as b', 'a.user_id','b.user_id')
            ->join('app_role as c', 'b.role_id','c.role_id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.user_active','1')
            ->where('b.role_id',$role)
            ->orderByDesc('a.user_id')
            ->first();
    }


    public static function insertAssignment($params) {
        return DB::table('branch_assignment')->insert($params);
    }

    public static function updateAssignment($id, $params) {
        return DB::table('branch_assignment')->where('id', $id)->update($params);
    }

    public static function updateAssignmentDetail($id, $params) {
        return DB::table('branch_assignment_detail')->where('id', $id)->update($params);
    }

}
