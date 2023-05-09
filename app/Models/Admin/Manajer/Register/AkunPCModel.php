<?php

namespace App\Models\Admin\Manajer\Register;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AkunPCModel extends BaseModel
{
    
    // get data with pagination
    public static function getDataWithPagination() {
        return DB::table('app_user as a')
            ->select('a.*','c.role_name as position')
            ->join('app_role_user as b', 'a.user_id','b.user_id')
            ->join('app_role as c', 'b.role_id','c.role_id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.user_active','1')
            ->orderByDesc('a.user_id')
            ->paginate(10);
    }

    // get search
    public static function getDataSearch($search) {
        return DB::table('app_user as a')
            ->select('a.*','c.role_name as position')
            ->join('app_role_user as b', 'a.user_id','b.user_id')
            ->join('app_role as c', 'b.role_id','c.role_id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.user_active','1')
            ->where('a.user_name', 'LIKE', "%".$search."%")
            ->orWhere('a.nik', 'LIKE', "%".$search."%")
            ->orWhere('c.role_name', 'LIKE', "%".$search."%")
            ->orWhere('a.no_telp', 'LIKE', "%".$search."%")
            ->orWhere('a.user_email', 'LIKE', "%".$search."%")
            ->orderByDesc('a.user_id')
            ->paginate(20)->withQueryString();
    }

    // get data by id
    public static function getDataById($id) {
        return DB::table('app_user as a')
            ->select('a.*','c.role_name as position')
            ->join('app_role_user as b', 'a.user_id','b.user_id')
            ->join('app_role as c', 'b.role_id','c.role_id')
            ->where('a.branch_id', Auth::user()->branch_id)
            ->where('a.user_active','1')
            ->where('a.user_id', $id)
            ->first();
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
        return DB::table('app_user')->insert($params);
    }

    public static function insert_role_user($params) {
        return DB::table('app_role_user')->insert($params);
    }


    public static function update($id, $params) {
        return DB::table('app_user')->where('user_id', $id)->update($params);
    }

    public static function delete($id) {
        return DB::table('app_user')->where('user_id', $id)->delete();
    }
}
