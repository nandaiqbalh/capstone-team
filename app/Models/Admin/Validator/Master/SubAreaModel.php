<?php

namespace App\Models\Admin\Validator\Master;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class SubAreaModel extends BaseModel
{
    
    // get all data
    public static function getAll() {
        return DB::table('master_sub_area as a')
                ->select('a.*','b.name as area_name')
                ->join('master_area as b','a.area_id','=','b.id')
                ->where('a.data_status', '1')
                ->orderBy('a.name','asc')
                ->get();
    }
    
    // get data with pagination
    public static function getAllPaginate() {
        return DB::table('master_sub_area as a')
                ->select('a.*','b.name as area_name')
                ->join('master_area as b','a.area_id','=','b.id')
                ->where('a.data_status', '1')
                ->orderBy('a.name','asc')
                ->paginate(10)
                ->withQueryString();
    }

    // get search
    public static function getAllSearch($query_all) {
        return DB::table('master_sub_area as a')
            ->select('a.*','b.name as area_name')
            ->join('master_area as b','a.area_id','=','b.id')
            ->where('a.name', 'LIKE', "%".$query_all."%")
            ->where('a.data_status', '1')
            ->orWhere(function($query) use($query_all) {
                $query->where('a.description', 'LIKE', "%".$query_all."%")
                    ->where('a.data_status', '1');
            })
            ->orWhere(function($query) use($query_all) {
                $query->where('b.name', 'LIKE', "%".$query_all."%")
                    ->where('a.data_status', '1');
            })
            ->orderBy('a.name','asc')
            ->paginate(10)->withQueryString();
    }

    // get data by id
    public static function getById($id) {
        return DB::table('master_sub_area as a')
                ->select('a.*','b.name as area_name')
                ->join('master_area as b','a.area_id','=','b.id')
                ->where('a.id', $id)
                ->where('a.data_status', '1')
                ->first();
    }

    public static function getByName($name) {
        return DB::table('master_sub_area as a')
                ->select('a.*','b.name as area_name')
                ->join('master_area as b','a.area_id','=','b.id')
                ->where('a.name', $name)
                ->where('a.data_status', '1')
                ->first();
    }

    public static function getByAreaAndName($id,$name) {
        return DB::table('master_sub_area as a')
                ->select('a.*','b.name as area_name')
                ->join('master_area as b','a.area_id','=','b.id')
                ->where('a.area_id', $id)
                ->where('a.name', $name)
                ->where('a.data_status', '1')
                ->first();
    }

    public static function insert($params) {
        return DB::table('master_sub_area')->insert($params);
    }

    public static function insert_or_ignore($params) {
        return DB::table('master_sub_area')->insertOrIgnore($params);
    }

    public static function update($id, $params) {
        return DB::table('master_sub_area')->where('id', $id)->update($params);
    }

    // public static function delete($id) {
    //     return DB::table('master_sub_area')->where('id', $id)->delete();
    // }

    // get master location
    public static function getMasterArea() {
        return DB::table('master_area as a')
                ->select('a.id','a.name','b.name as location_name')
                ->join('master_location as b','a.location_id','=','b.id')
                ->where('a.data_status', '1')
                ->orderBy('a.name','asc')
                ->get();
    }

    // get area by name
    public static function getAreaByName($name) {
        return DB::table('master_area as a')
                ->select('a.id','a.name','b.name as location_name')
                ->join('master_location as b','a.location_id','=','b.id')
                ->where('a.name', $name)
                ->where('a.data_status', '1')
                ->first();
    }
}
