<?php

namespace App\Models\Admin\Manajer\Branch;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class ItemPCModel extends BaseModel
{

    // get data sub area with pagination
    public static function getDataItemPagination($id) {
        return DB::table('branch_items as a')
            ->select('e.round_id as ronde','a.id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area','a.unique_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->orderBy('e.round_id')
            ->orderBy('e.name')
            ->paginate(10);
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
    public static function getRSbyId($id) {
        return DB::table('master_branch')
            ->select('id','name')
            ->where('id', $id)
            ->first();
    }
    
    // get search
    public static function getDataSearch($id,$search) {
        return DB::table('branch_items as a')
            ->select('e.round_id as ronde','a.id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area','a.unique_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->where('c.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->orWhere('a.zona', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->orWhere('a.id', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->orWhere('d.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->orWhere('e.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->orWhere('f.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->orderBy('e.round_id')
            ->orderBy('e.name')
            ->paginate(20)->withQueryString();
    }
    // get search
    public static function getDataRound($id,$round) {
        return DB::table('branch_items as a')
            ->select('e.round_id as ronde','a.id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area','a.unique_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->where('a.branch_id', $id)
            ->orderByDesc('a.id')
            ->paginate(20)->withQueryString();
    }
    // get search
    public static function getDataSearchRound($id,$search,$round) {
        return DB::table('branch_items as a')
            ->select('e.round_id as ronde','a.id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area','a.unique_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->where('c.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orWhere('a.zona', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orWhere('a.id', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orWhere('d.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orWhere('e.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orWhere('f.name', 'LIKE', "%".$search."%")
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->where('e.round_id', 'LIKE', "%".$round."%")
            ->orderByDesc('a.id')
            ->paginate(20)->withQueryString();
    }
    // get data item
    public static function getDataItemAllRS($id) {
        return DB::table('branch_items as a')
            ->select('e.round_id as ronde','a.id','a.zona','b.name as nama_rs','c.name as nama_item','f.name as nama_lokasi','e.name as nama_area','d.name as nama_sub_area','a.unique_id')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', $id)
            ->where('a.data_status','1')
            ->orderBy('e.round_id')
            ->orderBy('e.name')
            ->get();
    }
    // get data Rs
    public static function getNamaRS($rs_id) {
        return DB::table('master_branch')
        ->where('id',$rs_id)
        ->first();
    }
}
