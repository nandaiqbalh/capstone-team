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
            ->join('app_role_user as b', 'a.id', 'b.id')
            ->join('app_role as c', 'b.role_id', 'c.role_id')
            ->where('c.role_id', '03')
            ->where('a.user_name', 'LIKE', "%" . $search . "%")
            // ->orwhere('a.nomor_induk', 'LIKE', "%" . $search . "%")
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

    public static function insertrole($params2)
    {
        return DB::table('app_role_user')->insert($params2);
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
