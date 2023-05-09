<?php

namespace App\Models\Admin\Settings;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class Role extends BaseModel
{
    
    // get all data
    public static function getAll() {
        return DB::table('app_role')->get();
    }
    
    // get data with pagination
    public static function getAllPaginate() {
        return DB::table('app_role')->paginate(10);
    }

    // get search
    public static function getAllSearch($role_name) {
        return DB::table('app_role')->where('role_name', 'LIKE', "%".$role_name."%")->paginate(10);
    }

    // get data by id
    public static function getById($id) {
        return DB::table('app_role')->where('role_id', $id)->first();
    }

    // get list user by role
    public static function getTotalUserByRoleId($role_id) {
        return DB::table('app_user as a')
            ->select('a.user_id','a.user_name','a.user_email', 'a.user_active','c.role_name')
            ->join('app_role_user as b', 'a.user_id', '=', 'b.user_id')
            ->join('app_role as c', 'b.role_id', '=', 'c.role_id')
            ->where('c.role_id', $role_id)
            ->orderBy('a.created_date')        
            ->count('a.user_id');
    }

    // short id
    public static function makeShortId() {
        // get last id
        $last_id = DB::table('app_role')->select('role_id')->orderByDesc('role_id')->first();
        // make new id
        if($last_id) {
            $role_id = str_pad($last_id->role_id + 1,2,'0', STR_PAD_LEFT );
        }
        else {
            $role_id = str_pad(1,2,'0', STR_PAD_LEFT );
        }
        return $role_id;
    }

    public static function insert($params) {
        return DB::table('app_role')->insert($params);
    }

    public static function update($id, $params) {
        return DB::table('app_role')->where('role_id', $id)->update($params);
    }

    public static function delete($id) {
        return DB::table('app_role')->where('role_id', $id)->delete();
    }
}
