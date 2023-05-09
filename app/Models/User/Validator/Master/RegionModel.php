<?php

namespace App\Models\Admin\Validator\Master;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class RegionModel extends BaseModel
{
    
    // get all data
    public static function getAll() {
        return DB::table('master_region')->where('data_status', '1')->orderBy('name','asc')->get();
    }
    
    // get data with pagination
    public static function getAllPaginate() {
        return DB::table('master_region')->where('data_status', '1')->orderBy('name','asc')->paginate(10)->withQueryString();
    }

    // get search
    public static function getAllSearch($query_all) {
        return DB::table('master_region')
            ->where('name', 'LIKE', "%".$query_all."%")
            ->where('data_status', '1')
            ->orWhere('description', 'LIKE', "%".$query_all."%")
            ->orderBy('name','asc')
            ->paginate(10)->withQueryString();
    }

    // get data by id
    public static function getById($id) {
        return DB::table('master_region')->where('id', $id)->where('data_status', '1')->orderByDesc('id')->first();
    }

    public static function getByName($name) {
        return DB::table('master_region')->where('name', $name)->where('data_status', '1')->first();
    }

    public static function insert($params) {
        return DB::table('master_region')->insert($params);
    }

    public static function update($id, $params) {
        return DB::table('master_region')->where('id', $id)->update($params);
    }

    // public static function delete($id) {
    //     return DB::table('master_region')->where('id', $id)->delete();
    // }

}
