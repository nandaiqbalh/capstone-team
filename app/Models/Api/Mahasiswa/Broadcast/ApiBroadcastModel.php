<?php

namespace App\Models\Api\Mahasiswa\Broadcast;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApiBroadcastModel extends Model
{
    // get all data
    public static function getData()
    {
        return DB::table('broadcast')
            ->get();
    }

    public static function getDataWithPagination()
    {
        return DB::table('broadcast')->orderBy('created_date', 'desc')->paginate(10);
    }

    public static function getDataWithHomePagination()
    {
        return DB::table('broadcast')->orderBy('created_date', 'desc')->paginate(3);
    }

    // get data by id
    public static function getDataById($id)
    {
        return DB::table('broadcast')->where('id', $id)->first();
    }

}
