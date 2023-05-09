<?php

namespace App\Models\Api\V1\Revisi;

use Illuminate\Support\Facades\DB;

class RevisiModel extends DB
{

    // get Round
    public static function getRound($id) {
        return DB::table('master_round')
            ->where('id','=',$id)
            ->first();
    }

    // get Email Verifikator 1
    public static function getBranchById($branch_id) {
    return DB::table('app_user as a')
        ->select('a.*','c.role_name as position','d.name as branch_name')
        ->join('app_role_user as b', 'a.user_id','b.user_id')
        ->join('app_role as c', 'b.role_id','c.role_id')
        ->join('master_branch as d','a.branch_id','id')
        ->where('a.branch_id', $branch_id)
        ->where('a.user_active','1')
        ->where('c.role_name','Verifikator 1')
        ->orderByDesc('a.user_id')
        ->first();
    }

}
