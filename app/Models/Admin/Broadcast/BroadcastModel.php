<?php

namespace App\Models\Admin\Broadcast;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\DB;

class BroadcastModel extends BaseModel
{
    // get all data
    public static function getData()
    {
        return DB::table('broadcast')
            ->get();
    }

    // get data with pagination
    public static function getDataWithPagination()
    {
        return DB::table('broadcast')
            ->paginate(20);
    }

    // get search
    public static function getDataSearch($search)
    {
        return DB::table('app_user as a')
            ->select('a.*', 'c.role_name')
            ->join('app_role as c', 'a.role_id', 'c.role_id')
            ->where('a.role_id', '03') // Filter berdasarkan role_id di tabel app_user
            ->where('a.user_name', 'LIKE', "%" . $search . "%")
            ->paginate(20)->withQueryString();

    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('broadcast')->where('id', $id)->first();
    }

    public static function insertbroadcast($params)
    {
        return DB::table('broadcast')->insert($params);
    }

    public static function update($id, $params)
    {
        return DB::table('broadcast')->where('id', $id)->update($params);
    }

    public static function delete($id)
    {
        return DB::table('broadcast')->where('id', $id)->delete();
    }
}
