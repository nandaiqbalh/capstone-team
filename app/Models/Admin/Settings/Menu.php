<?php

namespace App\Models\Admin\Settings;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class Menu extends BaseModel
{

    // get all data
    public static function getAll() {
        return DB::table('app_menu')->orderBy('menu_sort','ASC')->get();
    }

    // get data with pagination
    public static function getAllPaginate() {
        return DB::table('app_menu')->orderBy('menu_sort','ASC')->paginate(20);
    }

    // get search
    public static function getAllSearch($menu_name) {
        return DB::table('app_menu')->where('menu_name', 'LIKE', "%".$menu_name."%")->orderBy('menu_sort','ASC')->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getById($id) {
        return DB::table('app_menu')->where('menu_id', $id)->first();
    }

    // short id
    public static function makeShortId() {
        // get last id
        $last_id = DB::table('app_menu')->select('menu_id')->latest('created_date')->first();
        // make new id
        if($last_id) {
            $menu_id = str_pad($last_id->menu_id + 1,2,'0', STR_PAD_LEFT );
        }
        else {
            $menu_id = str_pad(1,2,'0', STR_PAD_LEFT );
        }
        return $menu_id;
    }

    // cek sub menu
    public static function cekSubMenu($menu_id) {
        $sub_menu = DB::table('app_menu')->where('parent_menu_id', $menu_id)->get();
        // cek
        if(count($sub_menu) == 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function insert($params) {
        return DB::table('app_menu')->insert($params);
    }

    public static function update($id, $params) {
        return DB::table('app_menu')->where('menu_id', $id)->update($params);
    }

    public static function delete($id) {
        return DB::table('app_menu')->where('menu_id', $id)->delete();
    }


    // get data role
    public static function getRole() {
        return DB::table('app_role')->select('role_id','role_name')->get();
    }

    // get data role
    public static function getRoleMenu($menu_id) {
        return DB::table('app_menu')->select('role_id','menu_id')->where('menu_id',$menu_id)->get();
    }

    public static function getMenuById($menu_id) {
        return DB::table('app_menu')->select('menu_id','menu_name')->where('menu_id',$menu_id)->first();
    }

    public static function insert_role_menu($params) {
        return DB::table('app_menu')->insert($params);
    }

    public static function delete_role_menu($id) {
        return DB::table('app_menu')->where('menu_id', $id)->where('role_id','!=', '01')->delete();
    }

}
