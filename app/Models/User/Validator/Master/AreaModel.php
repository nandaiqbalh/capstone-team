<?php

namespace App\Models\Admin\Validator\Master;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class AreaModel extends BaseModel
{
    
    // get all data
    public static function getAll() {
        return DB::table('master_area as a')
                ->select('a.*','b.name as location_name')
                ->join('master_location as b','a.location_id','=','b.id')
                ->where('a.data_status', '1')
                ->where('b.data_status', '1')
                ->orderBy('a.name','asc')
                ->get();
    }
    
    // get data with pagination
    public static function getAllPaginate() {
        return DB::table('master_area as a')
                ->select('a.*','b.name as location_name')
                ->join('master_location as b','a.location_id','=','b.id')
                ->where('a.data_status', '1')
                ->where('b.data_status', '1')
                ->orderBy('a.name','asc')
                ->paginate(10)->withQueryString();
    }

    // get search
    public static function getAllSearch($query_all) {
        return DB::table('master_area as a')
            ->select('a.*','b.name as location_name')
            ->join('master_location as b','a.location_id','=','b.id')
            ->where('a.name', 'LIKE', "%".$query_all."%")
            ->where('a.data_status', '1')
            ->where('b.data_status', '1')
            ->orWhere(function($query) use($query_all) {
                $query->where('a.description', 'LIKE', "%".$query_all."%")
                ->where('a.data_status', '1')
                ->where('b.data_status', '1');
            })
            ->orWhere(function($query) use($query_all) {
                $query->where('b.name', 'LIKE', "%".$query_all."%")
                ->where('a.data_status', '1')
                ->where('b.data_status', '1');
            })
            ->orderBy('a.name','asc')
            ->paginate(10)
            ->withQueryString();
    }

    // get data by id
    public static function getById($id) {
        return DB::table('master_area as a')
                ->select('a.*','b.name as location_name','c.name as round_name')
                ->join('master_location as b','a.location_id','=','b.id')
                ->leftJoin('master_round as c','a.round_id','=','c.id')
                ->where('a.id', $id)
                ->where('a.data_status', '1')
                ->where('b.data_status', '1')
                ->first();
    }

    public static function getByName($name) {
        return DB::table('master_area as a')
                ->select('a.*','b.name as location_name')
                ->join('master_location as b','a.location_id','=','b.id')
                ->where('a.name', $name)
                ->where('a.data_status', '1')
                ->where('b.data_status', '1')
                ->first();
    }

    public static function getByLocationAndName($id,$name) {
        return DB::table('master_area as a')
                ->select('a.*','b.name as location_name')
                ->join('master_location as b','a.location_id','=','b.id')
                ->where('a.location_id', $id)
                ->where('a.name', $name)
                ->where('a.data_status', '1')
                ->where('b.data_status', '1')
                ->first();
    }

    public static function insert($params) {
        return DB::table('master_area')->insert($params);
    }

    public static function insert_or_ignore($params) {
        return DB::table('master_area')->insertOrIgnore($params);
    }

    public static function update($id, $params) {
        return DB::table('master_area')->where('id', $id)->update($params);
    }

    // public static function delete($id) {
    //     return DB::table('master_area')->where('id', $id)->delete();
    // }

    // get total sub area by area
    public static function getTotalSubAreaByArea($area_id) {
        return DB::table('master_sub_area')->where('area_id', $area_id)->where('data_status', '1')->count('id');
    }

    // get list sub area by area
    public static function getSubAreaByArea($area_id) {
        return DB::table('master_sub_area')->where('area_id', $area_id)->where('data_status', '1')->orderBy('name','asc')->paginate(10);
    }

    // get master location
    public static function getMasterLocation() {
        return DB::table('master_location')->select('id','name')->where('data_status', '1')->orderBy('name','asc')->get();
    }

    // get location by name
    public static function getLocationByName($name) {
        return DB::table('master_location')
            ->select('id','name')
            ->where('name',$name)
            ->where('data_status', '1')
            ->first();
    }

    // get master round
    public static function getMasterRound() {
        return DB::table('master_round')->select('id','name')->orderBy('name','asc')->get();
    }
}
