<?php

namespace App\Models\Admin\Validator\Branch;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class BranchModel extends BaseModel
{
    
    // get all data
    public static function getAll() {
        return DB::table('master_branch as a')
            ->select('a.*','b.name as province_name','c.name as city_name')
            ->join('master_province as b','a.province_id','=','b.id')
            ->join('master_city as c','a.city_id','=','c.id')
            ->where('a.data_status', '1')
            ->orderBy('a.name','asc')
            ->get();
    }
    
    // get data with pagination
    public static function getAllPaginate() {
        return DB::table('master_branch as a')
            ->select('a.*','b.name as province_name','c.name as city_name')
            ->join('master_province as b','a.province_id','=','b.id')
            ->join('master_city as c','a.city_id','=','c.id')
            ->where('a.data_status', '1')
            ->orderBy('a.name','asc')
            ->paginate(10)->withQueryString();
    }

    // get search
    public static function getAllSearch($search_string) {
        return DB::table('master_branch as a')
            ->select('a.*','b.name as province_name','c.name as city_name')
            ->join('master_province as b','a.province_id','=','b.id')
            ->join('master_city as c','a.city_id','=','c.id')
            ->where('a.name', 'LIKE', "%".$search_string."%")
            ->where('a.data_status', '1')
            ->orWhere('a.address', 'LIKE', "%".$search_string."%")
            ->orWhere('a.no_telp', 'LIKE', "%".$search_string."%")
            ->orWhere('a.region_name', 'LIKE', "%".$search_string."%")
            ->orWhere('a.id_branch', 'LIKE', "%".$search_string."%")
            ->orWhere('b.name', 'LIKE', "%".$search_string."%")
            ->orWhere('c.name', 'LIKE', "%".$search_string."%")
            ->orderBy('a.name','asc')
            ->paginate(10)->withQueryString();
    }

    // get data by id
    public static function getById($id) {
        return DB::table('master_branch as a')
        ->select('a.*','b.name as province_name','c.name as city_name')
            ->join('master_province as b','a.province_id','=','b.id')
            ->join('master_city as c','a.city_id','=','c.id')
            ->where('a.id', $id)
            ->where('a.data_status', '1')
            ->first();
    }

    // get data by id
    public static function getByName($name) {
        return DB::table('master_branch as a')
        ->select('a.*','b.name as province_name','c.name as city_name')
            ->join('master_province as b','a.province_id','=','b.id')
            ->join('master_city as c','a.city_id','=','c.id')
            ->where('a.name', $name)
            ->where('a.data_status', '1')
            ->first();
    }

    public static function insert($params) {
        return DB::table('master_branch')->insert($params);
    }

    public static function insert_or_ignore($params) {
        return DB::table('master_branch')->insertOrIgnore($params);
    }

    // insert branch assessment
    public static function insertBranchAssessment($params) {
        return DB::table('branch_assessment')->insert($params);
    }

    public static function update($id, $params) {
        return DB::table('master_branch')->where('id', $id)->update($params);
    }

    // public static function delete($id) {
    //     return DB::table('master_branch')->where('id', $id)->delete();
    // }

    // get master region
    public static function getMasterRegion() {
        return DB::table('master_region')->where('data_status','1')->orderBy('name','asc')->get();
    }

    // get region by name
    public static function getRegionById($id) {
        return DB::table('master_region')->select('id','name')->where('id',$id)->where('data_status','1')->first();
    }

    // get provinsi
    public static function getMasterProvince() {
        return DB::table('master_province')->orderBy('name','asc')->get();
    }

    // get provinsi by name
    public static function getProvinceByName($name) {
        return DB::table('master_province')->select('id','name')->where('name',$name)->first();
    }

    // get province code by province id
    public static function getProvinceCodeById($province_id) {
        return DB::table('master_province')->where('id', $province_id)->value('code');
    }

    // get city by province
    public static function getMasterCityByProvince($province_code) {
        return DB::table('master_city')->select('id','name')->where('province_code', $province_code)->orderBy('name','asc')->get();
    }

    // get city by name
    public static function getCityByName($name) {
        return DB::table('master_city')->select('id','name')->where('name',$name)->first();
    }

    // get user by email
    public static function getUserByEmail($email) {
        return DB::table('app_user')
                ->where('user_email', $email)
                ->where('user_active','1')
                ->first();
    }

    // get user by nik
    public static function getUserByNik($nik) {
        return DB::table('app_user')
                ->where('nik', $nik)
                ->where('user_active','1')
                ->first();
    }

    // get user by id
    public static function getUserById($id) {
        return DB::table('app_user')
                ->where('user_id', $id)
                ->where('user_active','1')
                ->first();
    }

    // get user by branch
    public static function getUserByBranchId($branch_id) {
        return DB::table('app_user as a')
                ->select('a.user_id','a.user_name', 'c.role_id','c.role_name')
                ->join('app_role_user as b', 'a.user_id', '=', 'b.user_id')
                ->join('app_role as c', 'b.role_id', '=', 'c.role_id')
                ->where('a.branch_id', $branch_id)
                ->where('a.user_active', '1')
                ->orderBy('c.role_id','asc')
                ->get();
    }

    // get checker by branch
    public static function getCheckerByBranch($branch_id) {
        return DB::table('app_user as a')
                ->select('a.user_id','a.user_name', 'c.role_id','c.role_name')
                ->join('app_role_user as b', 'a.user_id', '=', 'b.user_id')
                ->join('app_role as c', 'b.role_id', '=', 'c.role_id')
                ->where('a.branch_id', $branch_id)
                ->where('c.role_id','02')
                ->where('a.user_active', '1')
                ->first();
    }

    // get direg name by regional name
    public static function getDiregNameByRegionalName($regional_name) {
        return DB::table('master_region')->where('name',$regional_name)->value('direg_name');
    }

    public static function insert_app_user($params) {
        return DB::table('app_user')->insert($params);
    }

    public static function insert_role_user($params) {
        return DB::table('app_role_user')->insert($params);
    }

    public static function update_app_user($id, $params) {
        return DB::table('app_user')->where('user_id', $id)->update($params);
    }

    public static function delete_app_user($id) {
        return DB::table('app_user')->where('user_id', $id)->delete();
    }
    // get data Ronde
    public static function getDataRound($start_day) {
        return DB::table('master_round')
            ->where('start_day','<=',$start_day)
            ->where('end_day','>=',$start_day)
            ->first();
    }
}
