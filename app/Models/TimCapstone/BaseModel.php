<?php

namespace App\Models\TimCapstone;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BaseModel
{
    // make microtime ID
    public static function makeMicrotimeID() {
        return str_replace('.','',microtime(true));
    }

    // get user menu access by url
    public static function getMenuAccessByUrl($url) {

        return DB::table('app_menu as a')
                ->join('app_role as b','b.role_id','=','a.role_id')
                ->join('app_user as c','b.role_id','=','a.role_id')
                ->where('a.menu_url', $url)
                ->where('c.user_id', Auth::user()->user_id)
                ->first();
    }

    /**
     * Authorize Permission
     * @type is C,R,U,D
     */
    public static function authorize($type) {

        // get role permission by user
        $permission = DB::table('app_role')
                        ->select('role_permission')
                        ->join('app_user', 'app_role.role_id','=', 'app_user.role_id')
                        ->where('user_id', Auth::user()->user_id)
                        ->first();

        // cek permission
        if(!empty($permission)) {
            if($type == 'C') {
                $C = substr($permission->role_permission, 0,1);
                if(!$C) {
                    return abort('403','Unauthorized Action');
                }
            }
            else if($type == 'R') {
                $R = substr($permission->role_permission, 1,1);
                if(!$R) {
                    return abort('403','Unauthorized Action');
                }
            }
            else if($type == 'U') {
                $U = substr($permission->role_permission, 2,1);
                if(!$U) {
                    return abort('403','Unauthorized Action');
                }
            }
            else if($type == 'D') {
                $D = substr($permission->role_permission, 3,1);
                if(!$D) {
                    return abort('403','Unauthorized Action');
                }
            }
            else {
                return abort('403','Unauthorized Action');
            }
        }
        else {
            return abort('403', 'Unauthorized Access');
        }
    }

    // user role
    public static function getUserRole() {
        $data = DB::table('app_role')
                ->select('role_name')
                ->join('app_user', 'app_role.role_id','=', 'app_user.role_id')
                ->where('user_id', Auth::user()->user_id)
                ->first();

        return $data;
    }

    // user role id
    public static function getUserRoleId() {
        $data = DB::table('app_role')
                ->join('app_user', 'app_role.role_id','=', 'app_user.role_id')
                ->where('user_id', Auth::user()->user_id)
                ->value('app_role.role_id');

        return $data;
    }

    // get parent menu utama
    public static function parentMenuUtama($user_id) {
        // get data
        $parent_menu_utama = DB::table('app_menu AS a')
                        ->select('a.menu_id', 'parent_menu_id', 'menu_name', 'menu_url', 'menu_icon')
                        ->join('app_user AS c', 'a.role_id','=', 'c.role_id')
                        ->whereNull('a.parent_menu_id')
                        ->where([
                            ['a.menu_display','=','1'],
                            ['a.menu_group', '=', 'utama'],
                            ['c.user_id', '=', $user_id],
                        ])
                        ->orderBy('a.menu_sort', 'ASC')
                        ->get();

        // return
        return $parent_menu_utama;
    }

    // get child menu utama
    public static function childMenuUtama($menu_id, $user_id) {
        // get data
        $child_menu_utama = DB::table('app_menu AS a')
                        ->select('a.menu_id', 'parent_menu_id', 'menu_name', 'menu_url', 'menu_icon')
                        ->join('app_user AS c', 'a.role_id','=', 'c.role_id')
                        ->where([
                            ['a.parent_menu_id','=', $menu_id],
                            ['a.menu_display','=','1'],
                            ['a.menu_group', '=', 'utama'],
                            ['c.user_id', '=', $user_id],
                        ])->orderBy('a.menu_sort', 'ASC')
                        ->get();

        // return
        return $child_menu_utama;
    }

    // get parent menu system
    public static function parentMenuSystem($user_id) {
        // get data
        $parent_menu_system = DB::table('app_menu AS a')
                        ->select('a.menu_id', 'parent_menu_id', 'menu_name', 'menu_url', 'menu_icon')
                        ->join('app_user AS c', 'a.role_id','=', 'c.role_id')
                        ->whereNull('a.parent_menu_id')
                        ->where([
                            ['a.menu_display','=','1'],
                            ['a.menu_group', '=', 'system'],
                            ['c.user_id', '=', $user_id],
                        ])
                        ->orderBy('a.menu_sort', 'ASC')
                        ->get();

        // return
        return $parent_menu_system;
    }

    // get child menu system
    public static function childMenuSystem($menu_id, $user_id) {
        // get data
        $child_menu_system = DB::table('app_menu AS a')
                        ->select('a.menu_id', 'parent_menu_id', 'menu_name', 'menu_url', 'menu_icon')
                        ->join('app_user AS c', 'a.role_id','=', 'c.role_id')
                        ->where([
                            ['a.parent_menu_id','=', $menu_id],
                            ['a.menu_display','=','1'],
                            ['a.menu_group', '=', 'system'],
                            ['c.user_id', '=', $user_id],
                        ])->orderBy('a.menu_sort', 'ASC')
                        ->get();

        // return
        return $child_menu_system;
    }

    // get menu parent url
    public static function getParentMenuUrl($parent_menu_id) {
        return DB::table('app_menu')->where('menu_id', $parent_menu_id)->value('menu_url');
    }

    // bulan indoensia
    public static function bulanIndo() {
        return array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
    }

}
