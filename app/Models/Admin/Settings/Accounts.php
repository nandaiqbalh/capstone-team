<?php

namespace App\Models\Admin\Settings;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class Accounts extends BaseModel
{
    // get all data
    public static function getAll()
    {
        return DB::table('app_user as a')
            ->select('a.user_id', 'a.user_name', 'a.user_email', 'a.user_active', 'c.role_name', 'nomor_induk')
            ->join('app_role_user as b', 'a.user_id', '=', 'b.user_id')
            ->join('app_role as c', 'b.role_id', '=', 'c.role_id')
            ->orderBy('a.user_name', 'asc')
            ->get();
    }

    // get data with pagination
    public static function getAllPaginate()
    {
        return DB::table('app_user as a')
            ->select('a.user_id', 'a.user_name', 'a.user_email', 'a.user_active', 'c.role_name', 'nomor_induk')
            ->join('app_role_user as b', 'a.user_id', '=', 'b.user_id')
            ->join('app_role as c', 'b.role_id', '=', 'c.role_id')
            ->orderBy('a.user_name', 'asc')
            ->paginate(20);
    }

    // get search
    public static function getAllSearch($user_name)
    {

            return DB::table('app_user as a')
                ->select('a.user_id', 'a.user_name', 'a.user_email', 'a.user_active', 'c.role_name', 'nomor_induk')
                ->join('app_role_user as b', 'a.user_id', '=', 'b.user_id')
                ->join('app_role as c', 'b.role_id', '=', 'c.role_id')
                ->where('user_name', 'LIKE', "%" . $user_name . "%")
                ->orderBy('a.user_name', 'asc')
                ->paginate(10)
                ->withQueryString();

    }

    // get data by id
    public static function getById($id)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_id')
            ->join('app_role_user as b', 'a.user_id', '=', 'b.user_id')
            ->join('app_role as c', 'b.role_id', '=', 'c.role_id')
            ->where('a.user_id', $id)
            ->orderBy('a.created_date')
            ->first();
    }

    // get data by email
    public static function getByEmail($email)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_id')
            ->join('app_role_user as b', 'a.user_id', '=', 'b.user_id')
            ->join('app_role as c', 'b.role_id', '=', 'c.role_id')
            ->where('a.user_email', $email)
            ->orderBy('a.created_date')
            ->first();
    }

    public static function insert($params)
    {
        return DB::table('app_user')->insert($params);
    }

    public static function update($id, $params)
    {
        return DB::table('app_user')->where('user_id', $id)->update($params);
    }

    public static function delete($id)
    {
        return DB::table('app_user')->where('user_id', $id)->delete();
    }

    // get role
    public static function getRole()
    {
        return DB::table('app_role')->select('role_id', 'role_name')->get();
    }

    public static function insert_role_user($params)
    {
        return DB::table('app_role_user')->insert($params);
    }

    public static function update_role_user($user_id, $params)
    {
        return DB::table('app_role_user')->where('user_id', $user_id)->update($params);
    }

}
