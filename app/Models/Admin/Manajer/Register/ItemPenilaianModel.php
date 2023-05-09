<?php

namespace App\Models\Admin\Checker\Register;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemPenilaianModel extends BaseModel
{
    // get data sub area 
    public static function getDataAllSubArea() {
        return DB::table('branch_items as a')
            ->select('c.name as nama_item','a.items_id','d.name as nama_sub_area','a.sub_area_id','a.unique_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->orderBy('e.round_id')
            ->orderBy('e.name')
            ->get();
    }

        // get data sub area 
        public static function getDataAllSubAreaBranch() {
            return DB::table('branch_items as a')
                ->select('d.name as nama_sub_area','a.sub_area_id')
                ->join('master_branch as b', 'a.branch_id','b.id')
                ->join('master_items as c', 'a.items_id','c.id')
                ->join('master_sub_area as d', 'a.sub_area_id','d.id')
                ->join('master_area as e', 'd.area_id','e.id')
                ->join('master_location as f', 'e.location_id','f.id')
                ->where('a.branch_id', Auth::user()->branch_id)
                ->where('a.data_status','1')
                ->orderBy('e.round_id')
            ->orderBy('e.name')
                ->get();
        }

    // get data sub area Uniq ID
    public static function getItemUniqueID($sub_area_id,$items_id) {
        return DB::table('branch_items as a')
            ->select('c.name as nama_item','a.items_id','d.name as nama_sub_area','a.sub_area_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('a.sub_area_id',$sub_area_id)
            ->where('a.items_id',$items_id)
            ->orderBy('e.round_id')
            ->orderBy('e.name')
            ->get();
    }

    // get data Branch Items
    public static function getLastItems() {
        return DB::table('branch_items')
            ->orderByDesc('id')
            ->first();
    }

    // get data Ronde
    public static function getDataRoundBranch($start_day,$month,$year,$branch_id) {
        return DB::table('branch_assessment as a')
            ->select('a.round_id','b.name as nama_ronde','b.id as id_ronde','a.id as branch_assessment_id','a.status as status','b.end_day as end_day','a.branch_id','c.name as branch_name')
            ->join('master_round as b','a.round_id','b.id')
            ->join('master_branch as c','a.branch_id','c.id')
            ->where('a.branch_id','=',$branch_id)
            ->where('b.start_day','<=',$start_day)
            ->where('b.end_day','>=',$start_day)
            ->whereMonth('a.created_date',$month)
            ->whereYear('a.created_date',$year)
            ->first();
    }
    // get komponen item
    public static function getKomponen($branch_id,$round_id,$items_id) {
        return DB::table('branch_items as a')
            ->select('a.id as branch_items_id','e.id as assessment_component_id')
            ->join('master_sub_area as b','a.sub_area_id','=','b.id')
            ->join('master_area as c','b.area_id','=','c.id')
            ->join('master_items as d','a.items_id','=','d.id')
            ->join('master_assessment_component as e','e.items_id','=','d.id')
            ->where('a.branch_id', $branch_id)
            ->where('c.round_id', $round_id)
            ->where('a.id', $items_id)
            ->where('a.data_status','1')
            ->where('e.data_status','1')
            ->orderBy('a.id','asc')
            ->orderBy('e.id','asc')
            ->get();
    }
    // get data sub area with pagination
    public static function getDataWithPagination() {
        return DB::table('branch_items as a')
            ->select('e.round_id as ronde','a.id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area','a.unique_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->orderBy('a.id')
            ->orderBy('e.round_id')
            ->orderBy('e.name')
            ->paginate(10, ['*'], 'faults_page');
    }
    // get data item
    public static function getDataItemAllRS() {
        return DB::table('branch_items as a')
            ->select('e.round_id as ronde','a.id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area','a.unique_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->orderBy('e.round_id')
            ->orderBy('e.name')
            ->get();
    }

    // get search
    public static function getDataSearch($search) {
        return DB::table('branch_items as a')
            ->select('e.round_id as ronde','a.id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area','a.unique_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('c.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->orWhere('a.zona', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->orWhere('a.id', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->orWhere('d.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->orWhere('e.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->orWhere('f.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->orderBy('a.id')
            ->orderBy('e.round_id')
            ->orderBy('e.name')
            ->paginate(10)->withQueryString();
    }
    // get search
    public static function getDataRound($round) {
        return DB::table('branch_items as a')
            ->select('e.round_id as ronde','a.id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area','a.unique_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->orderBy('e.round_id')
            ->orderBy('e.name')
            ->paginate(10)->withQueryString();
    }
    // get search
    public static function getDataSearchRound($search,$round) {
        return DB::table('branch_items as a')
            ->select('e.round_id as ronde','a.id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area','a.unique_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->where('c.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orWhere('a.zona', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orWhere('a.id', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orWhere('d.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orWhere('e.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orWhere('f.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orderBy('a.id')
            ->orderBy('e.round_id')
            ->orderBy('e.name')
            ->paginate(10)->withQueryString();
    }
    // get data by id
    public static function getDataById($id) {
        return DB::table('branch_items as a')
            ->select('e.round_id as ronde','a.id','a.items_id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area','a.unique_id','a.sub_area_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('a.id', $id)
            ->orderBy('e.round_id')
            ->orderBy('e.name')
            ->first();
    }

    // get data Location Master
    public static function getDataLocation() {
        return DB::table('master_location')
        ->where('data_status','1')
        ->get();
    }
    
    // get data Area Master
    public static function getDataArea($location_id) {
        return DB::table('master_area')
        ->where('location_id',$location_id)
        ->where('data_status','1')
        ->get();
    }

    // get data Sub Area Master
    public static function getDataSubArea($area_id) {
        return DB::table('master_sub_area')
        ->where('data_status','1')
        ->where('area_id',$area_id)
        ->get();
    }
    
    // get data Item Master
    public static function getDataItem() {
        return DB::table('master_items')
        ->where('data_status','1')->get();
    }
    // get data Komponen
    public static function getDataComponent($item_id) {
        return DB::table('master_assessment_component')
        ->where('data_status','1')
        ->where('items_id',$item_id)
        ->paginate(30);
    }

    // get data by email
    public static function getByEmail($email) {
        return DB::table('app_user')->where('user_email', $email)->first();
    }

    // get data Rs
    public static function getNamaRS($rs_id) {
        return DB::table('master_branch')
        ->where('id',$rs_id)
        ->first();
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
    public static function insert($params) {
        return DB::table('branch_items')->insert($params);
    }
    public static function insertAssessmentDetail($params) {
        return DB::table('branch_assessment_detail')->insert($params);
    }
    public static function update($id, $params) {
        return DB::table('branch_items')->where('id', $id)->update($params);
    }

    public static function delete($id, $params) {
        return DB::table('branch_items')->where('id', $id)->update($params);
    }

    public static function AssessmentDetailDelete($id, $assessment_id) {
        return DB::table('branch_assessment_detail')->where('branch_items_id', $id)->where('branch_assessment_id', $assessment_id)->delete();
    }

    // get data komponen yang sudah dinilai
    public static function getDataAssessment($round_id) {
        return DB::table('branch_assessment_detail as a')
            ->select('a.id','c.unique_id','a.description as keterangan','d.name as nama_item','g.name as nama_lokasi','f.name as nama_area','e.name as nama_sub_area','h.name as nama_komponen','a.img_name as nama_gambar','a.revision_status as status_revisi','a.score as nilai','a.parameter as parameter','a.revision_description as revisi_deskripsi')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.data_status', '0')
            ->whereMonth('c.created_date',date('m'))
            ->whereYear('c.created_date',date('Y'))
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id',$round_id)
            ->orderByDesc('a.id')
            ->get();
    }

    // get data komponen yang sudah dinilai
    public static function getDataAssessmentNow($assessment_id)
    {
        return DB::table('branch_assessment')
        ->where('id',$assessment_id)
        ->orderByDesc('id')
        ->first();
    }
}
