<?php

namespace App\Models\Admin\Validator\Master;

use Illuminate\Support\Facades\DB;
use App\Models\Admin\BaseModel;

class ItemsModel extends BaseModel
{
    
    // get all data
    public static function getAll() {
        return DB::table('master_items')->where('data_status', '1')->orderBy('name','asc')->get();
    }
    
    // get data with pagination
    public static function getAllPaginate() {
        return DB::table('master_items')->where('data_status', '1')->orderBy('name','asc')->paginate(10)->withQueryString();
    }

    // get search
    public static function getAllSearch($query_all) {
        return DB::table('master_items')
            ->where('name', 'LIKE', "%".$query_all."%")
            ->where('data_status', '1')
            ->orWhere(function($query) use($query_all) {
                $query->where('description', 'LIKE', "%".$query_all."%")
                    ->where('data_status', '1');
            })
            ->orderBy('name','asc')
            ->paginate(10)
            ->withQueryString();
    }

    // get data by id
    public static function getById($id) {
        return DB::table('master_items')->where('id', $id)->where('data_status', '1')->first();
    }

    public static function getByName($name) {
        return DB::table('master_items')->where('name', $name)->where('data_status', '1')->first();
    }

    public static function insert($params) {
        return DB::table('master_items')->insert($params);
    }

    public static function insert_or_ignore($params) {
        return DB::table('master_items')->insertOrIgnore($params);
    }

    public static function update($id, $params) {
        return DB::table('master_items')->where('id', $id)->update($params);
    }

    // public static function delete($id) {
    //     return DB::table('master_items')->where('id', $id)->delete();
    // }

    // get total
    public static function getTotalItemComponentByItems($items_id) {
        return DB::table('master_assessment_component')->where('items_id', $items_id)->where('data_status', '1')->count('id');
    }

    // get list
    public static function getItemComponentByItems($items_id) {
        return DB::table('master_assessment_component')->where('items_id', $items_id)->where('data_status', '1')->orderBy('name','asc')->paginate(10);
    }
}
