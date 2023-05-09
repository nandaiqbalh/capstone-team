<?php

namespace App\Models\Admin\Checker\QRCode;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QRCodeModel extends BaseModel
{
        // get data with pagination
    public static function getDataAllSubArea() {
        return DB::table('branch_items as a')
            ->select('d.name as nama_sub_area','a.sub_area_id','e.name as nama_area')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->where('d.data_status','1')
            ->orderByDesc('a.id')
            ->get();
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
    public static function getDataSearch($search) {
        return DB::table('branch_items as a')
            ->select('d.name as nama_sub_area','a.sub_area_id','e.name as nama_area')
            ->join('master_branch as b', 'a.branch_id','b.id')
            ->join('master_items as c', 'a.items_id','c.id')
            ->join('master_sub_area as d', 'a.sub_area_id','d.id')
            ->join('master_area as e', 'd.area_id','e.id')
            ->join('master_location as f', 'e.location_id','f.id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.data_status','1')
            ->Where('d.name', 'LIKE', "%".$search."%")
            ->orWhere('e.name', 'LIKE', "%".$search."%")
            ->orderByDesc('a.id')
            ->get();
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

    
    // get data by id
    public static function getDataSubAreaName($id) {
        return DB::table('master_sub_area')
            ->where('id',$id)
            ->value('name');
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
    public static function insert($params) {
        return DB::table('branch_items')->insert($params);
    }

    public static function update($id, $params) {
        return DB::table('branch_items')->where('id', $id)->update($params);
    }

    public static function delete($id) {
        return DB::table('app_user')->where('user_id', $id)->delete();
    }
}
