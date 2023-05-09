<?php

namespace App\Models\Api\V1\TriggerNode;

use Illuminate\Support\Facades\DB;

class TriggerNodeModel extends DB
{
    //  Round check
    public static function RoundCheck($start_day, $month, $year, $branch_id)
    {
        return DB::table('branch_assessment as a')
            ->select('b.name as nama_ronde', 'b.id as id_ronde', 'a.id as branch_assessment_id', 'a.status as status', 'b.start_day as start_day', 'b.end_day as end_day')
            ->join('master_round as b', 'a.round_id', 'b.id')
            ->where('a.branch_id', $branch_id)
            ->where('b.start_day', '<=', $start_day)
            ->where('b.end_day', '>=', $start_day)
            ->whereMonth('a.created_date', $month)
            ->whereYear('a.created_date', $year)
            ->orderByDesc('a.id')
            ->first();
    }

    //  DL checker
    public static function DeadlineChecker($day)
    {
        return DB::table('branch_assessment as a')
            ->select('b.name as nama_ronde', 'b.id as id_ronde', 'a.id as branch_assessment_id', 'a.status as status', 'b.end_day as end_day')
            ->join('master_round as b', 'a.round_id', 'b.id')
            ->where('b.deadline_checker_day', '=', $day)
            ->where('a.status', 'proses penilaian')
            ->orderByDesc('a.id')
            ->get();
    }
    //  DL Verifikator 1
    public static function DeadlineVerifikator1($day)
    {
        return DB::table('branch_assessment as a')
            ->select('b.name as nama_ronde', 'b.id as id_ronde', 'a.id as branch_assessment_id', 'a.status as status', 'b.end_day as end_day')
            ->join('master_round as b', 'a.round_id', 'b.id')
            ->where('b.deadline_verifikator_1_day', '=', $day)
            ->where('a.verifikator_1_approved_date', '=', null)
            ->orderByDesc('a.id')
            ->get();
    }
    //  Verifikator 1
    public static function getVerifikator1($id)
    {
        return DB::table('app_user as a')
            ->select('a.user_name', 'b.*')
            ->join('app_role_user as b', 'a.user_id', 'b.user_id')
            ->where('a.branch_id', '=', $id)
            ->where('b.role_id', '=', '03')
            ->where('a.user_active', '1')
            ->first();
    }
    //  Verifikator 2
    public static function getVerifikator2($id)
    {
        return DB::table('app_user as a')
            ->select('a.user_name', 'b.*')
            ->join('app_role_user as b', 'a.user_id', 'b.user_id')
            ->where('a.branch_id', '=', $id)
            ->where('b.role_id', '=', '04')
            ->where('a.user_active','1')
            ->first();
    }

    //  DL Rumah Sakit
    public static function DeadlineBranch($day)
    {
        return DB::table('branch_assessment as a')
            ->select('b.name as nama_ronde', 'b.id as id_ronde', 'a.id as branch_assessment_id', 'a.status as status', 'b.end_day as end_day', 'a.verifikator_2_approved_date')
            ->join('master_round as b', 'a.round_id', 'b.id')
            ->where('b.end_day', '>=', $day)
            ->where('a.verifikator_2_approved_date', '=', null)
            ->orWhere('a.status', '=', "Proses Penilaian")
            ->orWhere('a.status', '=', "Persetujuan Verifikator 1")
            ->orWhere('a.status', '=', "Persetujuan Verifikator 2")
            ->orderByDesc('a.id')
            ->get();
    }

    // get Round
    public static function getRound($start_day)
    {
        return DB::table('master_round')
            ->where('start_day', '<=', $start_day)
            ->where('end_day', '>=', $start_day)
            ->first();
    }

    // get DiregName
    public static function getDireg($regional_name)
    {
        return DB::table('master_region as a')
            ->select('a.direg_name')
            ->join('master_branch as b', 'a.name', 'b.region_name')
            ->where('a.name', $regional_name)
            ->first();
    }
    // get Dirop dan Validator
    public static function getDirop_Validator()
    {
        return DB::table('app_supports')
            ->get();
    }

    // get data komponen Round 
    public static function getKomponenRound($branch_id, $round_id, $day)
    {
        return DB::table('master_assessment_component as a')
            ->select('a.name as nama_komponen', 'c.name as items_name', 'b.id as branch_item_id', 'a.id as assessment_component_id')
            ->join('branch_items as b', 'a.items_id', 'b.items_id')
            ->join('master_items as c', 'a.items_id', 'c.id')
            ->join('master_sub_area as d', 'b.sub_area_id', 'd.id')
            ->join('master_area as e', 'd.area_id', 'e.id')
            ->join('master_round as f', 'e.round_id', 'f.id')
            ->where('f.start_day', $day)
            ->where('b.branch_id', $branch_id)
            ->where('b.data_status', '1')
            ->where('e.round_id', $round_id)
            ->orderByDesc('a.id')
            ->get();
    }

    // get komponen item
    public static function getKomponenItem($branch_id, $round_id)
    {
        return DB::table('branch_items as a')
            ->select('a.id as branch_items_id', 'e.id as assessment_component_id')
            ->join('master_sub_area as b', 'a.sub_area_id', '=', 'b.id')
            ->join('master_area as c', 'b.area_id', '=', 'c.id')
            ->join('master_items as d', 'a.items_id', '=', 'd.id')
            ->join('master_assessment_component as e', 'e.items_id', '=', 'd.id')
            ->where('a.branch_id', $branch_id)
            ->where('c.round_id', $round_id)
            ->where('a.data_status', '1')
            ->orderBy('a.id', 'asc')
            ->orderBy('e.id', 'asc')
            ->get();
    }

    // get data komponen yang sudah dinilai
    public static function cekAssessmentDetail($round_id)
    {
        return DB::table('branch_assessment_detail as a')
            ->select('c.unique_id', 'a.description as keterangan', 'd.name as nama_item', 'g.name as nama_lokasi', 'f.name as nama_area', 'e.name as nama_sub_area', 'h.name as nama_komponen', 'a.img_name as nama_gambar', 'a.revision_status as status_revisi', 'a.score as nilai', 'a.parameter as parameter', 'a.revision_description as revisi_deskripsi')
            ->join('branch_assessment as b', 'a.branch_assessment_id', 'b.id')
            ->join('branch_items as c', 'a.branch_items_id', 'c.id')
            ->join('master_items as d', 'c.items_id', 'd.id')
            ->join('master_sub_area as e', 'c.sub_area_id', 'e.id')
            ->join('master_area as f', 'e.area_id', 'f.id')
            ->join('master_location as g', 'f.location_id', 'g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id', 'h.id')
            // ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id', $round_id)
            ->where('c.data_status', '1')
            ->where('h.data_status', '1')
            ->orderByDesc('a.id')
            ->paginate(10);
    }

    // get branch assessment
    public static function getBranchAssessment($id,$round_id, $month, $year)
    {
        return DB::table('branch_assessment')
            ->where('branch_id', $id)
            ->where('data_status', '1')
            ->where('round_id', $round_id)
            ->whereMonth('created_date', $month)
            ->whereYear('created_date', $year)
            ->get();
    }
    // get branch assessment
    public static function getBranchAssignment($id, $round_id)
    {
        return DB::table('branch_assignment')
        ->where('branch_id', $id)
        ->where('round_id', $round_id)
        ->orderByDesc('id')
        ->first();
    }
    // get branch assessment
    public static function checkAssignment($id)
    {
        return DB::table('branch_assignment')
            ->where('branch_assessment_id', $id)
            ->get();
    }
    // get branch assessment
    public static function checkAssignmentDetail($id)
    {
        return DB::table('branch_assignment_detail')
            ->where('assessment_detail_id', $id)
            ->get();
    }

    // get branch assessment
    public static function getAssignmentId($id)
    {
        return DB::table('branch_assignment')
            ->where('branch_assessment_id', $id)
            ->first();
    }
    // get branch assessment
    public static function getBranchAssessmentDetail($id)
    {
        return DB::table('branch_assessment_detail')
            ->where('branch_assessment_id', $id)
            ->where(function ($query) {
                $query->where('score', 'B')
                    ->orWhere('score', 'C');
            })->get();
    }

    // public static function getKomponenRoundGenerate($branch_id,$round_id) {
    //     return DB::table('master_assessment_component as a')
    //         ->select('a.name as nama_komponen','c.name as items_name')
    //         ->join('branch_items as b','a.items_id','b.items_id')
    //         ->join('master_items as c', 'a.items_id','c.id')
    //         ->join('master_sub_area as d', 'b.sub_area_id','d.id')
    //         ->join('master_area as e', 'd.area_id','e.id')
    //         ->join('master_round as f', 'e.round_id','f.id')
    //         ->where('b.branch_id', $branch_id)
    //         ->where('b.data_status','1')
    //         ->where('e.round_id',$round_id)
    //         ->orderByDesc('a.id')
    //         ->get();
    //     }
    // // get data komponen Round 2

    // public static function getKomponenRound2() {
    //     return DB::table('master_assessment_component as a')
    //         ->select('a.name as nama_komponen','c.name as items_name')
    //         ->join('branch_items as b','a.items_id','b.items_id')
    //         ->join('master_items as c', 'a.items_id','c.id')
    //         ->join('master_sub_area as d', 'b.sub_area_id','d.id')
    //         ->join('master_area as e', 'd.area_id','e.id')
    //         ->where('b.branch_id', Auth::user()->branch_id)
    //         ->where('b.data_status','1')
    //         ->where('e.round_id','2')
    //         ->orderByDesc('a.id')
    //         ->count();
    // }
    // //         // get data komponen Round 3
    // public static function getKomponenRound3() {
    //     return DB::table('master_assessment_component as a')
    //         ->select('a.name as nama_komponen','c.name as items_name')
    //         ->join('branch_items as b','a.items_id','b.items_id')
    //         ->join('master_items as c', 'a.items_id','c.id')
    //         ->join('master_sub_area as d', 'b.sub_area_id','d.id')
    //         ->join('master_area as e', 'd.area_id','e.id')
    //         ->where('b.branch_id', Auth::user()->branch_id)
    //         ->where('b.data_status','1')
    //         ->where('e.round_id','3')
    //         ->orderByDesc('a.id')
    //         ->count();
    // }
    // // get data komponen Round 4
    // public static function getKomponenRound4() {
    //     return DB::table('master_assessment_component as a')
    //         ->select('a.name as nama_komponen','c.name as items_name')
    //         ->join('branch_items as b','a.items_id','b.items_id')
    //         ->join('master_items as c', 'a.items_id','c.id')
    //         ->join('master_sub_area as d', 'b.sub_area_id','d.id')
    //         ->join('master_area as e', 'd.area_id','e.id')
    //         ->where('b.branch_id', Auth::user()->branch_id)
    //         ->where('b.data_status','1')
    //         ->where('e.round_id','4')
    //         ->orderByDesc('a.id')
    //         ->count();
    // }


    public static function getMasterBranchID()
    {
        return DB::table('master_branch')->select('id', 'region_name')->get();
    }
    public static function insert($params)
    {
        return DB::table('branch_assessment')->insert($params);
    }
    public static function insertAssessmentDetail($params)
    {
        return DB::table('branch_assessment_detail')->insert($params);
    }

    public static function insertAssignment($data)
    {
        return DB::table('branch_assignment')->insert($data);
    }
    public static function insertAssignmentDetail($data)
    {
        return DB::table('branch_assignment_detail')->insert($data);
    }
    public static function update($id, $params)
    {
        return DB::table('branch_assessment')->where('id', $id)->update($params);
    }
    public static function updateBranchAssessment($id, $params)
    {
        return DB::table('branch_assessment')
            ->where('id', $id)
            ->update($params);
    }

    // update assigment status 
    public static function updateAssignment($id, $params)
    {
        return DB::table('branch_assignment')
        ->where('id',
            $id
        )
        ->update($params);
    }
    //update approved by system RS
    public static function updateBranchApprovedSystem($id, $params)
    {
        return DB::table('branch_assessment')
            ->where('id', $id)
            ->update($params);
    }

    public static function delete($id)
    {
        return DB::table('branch_assessment')->where('id', $id)->delete();
    }
}
