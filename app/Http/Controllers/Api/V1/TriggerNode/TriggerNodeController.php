<?php

namespace App\Http\Controllers\Api\V1\TriggerNode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\Api\V1\TriggerNode\TriggerNodeModel;
use Illuminate\Support\Facades\DB;

class TriggerNodeController extends Controller
{
    /**
     * Triger Node JS.
     *
     * @return \Illuminate\Http\Response
     */
    public function Trigger()
    {
        $this->Round();
        $this->ApprovedBySystem();
        $this->CheckerLate();
        $this->CreateAssignment();


        // $this->cekAssessmentDetail();
        // $this->CheckerOntime();
        // return json
        $response = [
            "status" => true,
            "message" => 'Assessment OK',
        ];

        return response()->json($response)->setStatusCode(200);
    }


    /**
     * Triger Node JS.
     *
     * @return \Illuminate\Http\Response
     */
    public function TriggerAssignment()
    {
        // $this->CreateAssignment();

        $response = [
            "status" => true,
            "message" => 'Assignment OK',
        ];

        return response()->json($response)->setStatusCode(200);
    }

    // Buat Ronde
    public function Round()
    {
        //add process
        $getMasterBranch = TriggerNodeModel::getMasterBranchID();

        // $getKomponenR1 = TriggerNodeModel::getKomponenRound1();

        foreach ($getMasterBranch as $key => $value) {
            if (TriggerNodeModel::getVerifikator1($value->id)) {
                $getVerifikator1    = TriggerNodeModel::getVerifikator1($value->id)->user_name;
            } else {
                $getVerifikator1 = null;
            }
            if (TriggerNodeModel::getVerifikator2($value->id)) {
                # code...
                $getVerifikator2    = TriggerNodeModel::getVerifikator2($value->id)->user_name;
            } else {
                $getVerifikator2 = null;
            }
            // dd($getVerifikator2);
            $RoundCheck         = TriggerNodeModel::RoundCheck(date('d'), date('m'), date('Y'), $value->id);
            $getRound           = TriggerNodeModel::getRound(date('d'));
            $getDireg           = TriggerNodeModel::getDireg($value->region_name);
            $getDirop_Validator = TriggerNodeModel::getDirop_Validator();
            $rs_komponen_item        = TriggerNodeModel::getKomponenItem($value->id, $getRound->id);
            // $getKomponen        = TriggerNodeModel::getKomponenRound($value->id,$getRound->id,date('d'));
            if (!$RoundCheck) {

                $params = [
                    'branch_id'             => $value->id,
                    'round_id'              => $getRound->id,
                    'verifikator_1_name'    => $getVerifikator1,
                    'verifikator_2_name'    => $getVerifikator2,
                    'direg_name'            => $getDireg->direg_name,
                    'dirop_name'            => $getDirop_Validator[5]->value,
                    'validator_name'        => $getDirop_Validator[4]->value,
                    'status'                => 'proses penilaian',
                    'created_by'            => 'System',
                    'created_date'          => date('Y-m-d H:i:s')
                ];

                if (TriggerNodeModel::insert($params)) {

                    $idAssessment = DB::getPdo()->lastInsertId();
                    foreach ($rs_komponen_item as $key => $value1) {
                        $params = [
                            'branch_assessment_id' => $idAssessment,
                            'branch_items_id' => $value1->branch_items_id,
                            'assessment_component_id' => $value1->assessment_component_id,
                            'created_by'   => 'System',
                            'created_date'  => date('Y-m-d H:i:s')
                        ];
                        TriggerNodeModel::insertAssessmentDetail($params);
                    }
                }
            }
        }
        // dd($RoundCheck, $DeadlineBranch);
    }

    //   Approved By System
    public function ApprovedBySystem()
    {
        $getMasterBranch = TriggerNodeModel::getMasterBranchID();

        // $getKomponenR1 = TriggerNodeModel::getKomponenRound1();

        foreach ($getMasterBranch as $key => $value) {
            $RoundCheck         = TriggerNodeModel::RoundCheck(date('d'), date('m'), date('Y'), $value->id);
            // ApprovedBySystem
            $DeadlineBranch = TriggerNodeModel::DeadlineBranch(date('d'));
            if ($DeadlineBranch) {
                foreach ($DeadlineBranch as $key => $value1) {
                    if ($value1->id_ronde != $RoundCheck->id_ronde) {
                        $params = [
                            'verifikator_2_approved_by_system' => date('Y-m-d H:i:s'),
                            'status'   => 'Selesai',
                            'modified_by'   => 'System',
                            'modified_date'  => date('Y-m-d H:i:s')
                        ];
                        TriggerNodeModel::updateBranchApprovedSystem($value1->branch_assessment_id, $params);
                    }
                }
            }
            // parent::sendMail($data);
        }
    }
    //  Checker late By System
    public function CheckerLate()
    {
        $DeadlineChecker = TriggerNodeModel::DeadlineChecker(date('d'));
        // dd($DeadlineChecker);
        if ($DeadlineChecker) {
            foreach ($DeadlineChecker as $key => $value) {
                # code...
                $params = [
                    'checker_late' => date('Y-m-d H:i:s'),
                    'modified_by'   => 'System',
                    'modified_date'  => date('Y-m-d H:i:s')
                ];
                TriggerNodeModel::updateBranchAssessment($value->branch_assessment_id, $params);
            }
        }
        // parent::sendMail($data);
    }

    //  Checker cekAssessmentDetail
    public function cekAssessmentDetail()
    {
        $getMasterBranch = TriggerNodeModel::getMasterBranchID();
        // dd($getMasterBranch);
        foreach ($getMasterBranch as $key => $value) {
            $cekAssessmentDetail = TriggerNodeModel::cekAssessmentDetail(date('d'));
            if ($cekAssessmentDetail) {
                foreach ($cekAssessmentDetail as $key => $value) {
                }
            }
        }
        // parent::sendMail($data);
    }
    //  Checker Ontime
    public function CheckerOntime()
    {
        $DeadlineChecker = TriggerNodeModel::DeadlineChecker(date('d'));

        if ($DeadlineChecker) {
            $params = [
                'a.checker_approved_by_system' => date('Y-m-d H:i:s'),
                'a.modified_by'   => 'System',
                'a.modified_date'  => date('Y-m-d H:i:s')
            ];
            TriggerNodeModel::updateBranchAssessment(date('d'), $params);
        }
    }
    // verifikator 1 deadline
    public function Verifikator1Deadline()
    {
        $DeadlineVerifikator1 = TriggerNodeModel::DeadlineChecker(date('d'));
        // dd($DeadlineVerifikator1);
        foreach ($DeadlineVerifikator1 as $key => $value) {
            if ($DeadlineVerifikator1) {
                $params = [
                    'verifikator_2_approved_by_system' => date('Y-m-d H:i:s'),
                    'modified_by'   => 'System',
                    'modified_date'  => date('Y-m-d H:i:s')
                ];
                TriggerNodeModel::updateBranchApprovedSystem($value->branch_assessment_id, $params);
            }
        }
    }


    // Pekerjaan
    public function CreateAssignment()
    {
        $getMasterBranch = TriggerNodeModel::getMasterBranchID();
        // master branch
        foreach ($getMasterBranch as $key => $value) {
            $RoundCheck         = TriggerNodeModel::RoundCheck(date('d'), date('m'), date('Y'), $value->id);
            $rs_assessment        = TriggerNodeModel::getBranchAssessment($value->id, $RoundCheck->id_ronde, date('m'), date('Y'));

            (($RoundCheck->id_ronde - 1) > 0) ? ($update_round_assignment = $RoundCheck->id_ronde - 1) : $update_round_assignment = 4;
            // update assigment data status 0 ke 1
            $get_assignment = TriggerNodeModel::getBranchAssignment($value->id, $update_round_assignment);
            
            if ($get_assignment) {
                $data = [
                    'status'                => 'Proses Pekerjaan',
                    'data_status'           => '1',
                    'modified_by'            => 'System',
                    'modified_date'          => date('Y-m-d H:i:s')
                ];
                TriggerNodeModel::updateAssignment($get_assignment->id,$data );
            }
            foreach ($rs_assessment as $key_assesment => $value_assessment) {
                $check_assignment = TriggerNodeModel::checkAssignment($value_assessment->id);
                // dd($check_assignment);
                if (count($check_assignment) < 1) {
                    $data = [
                        'branch_assessment_id'  => $value_assessment->id,
                        'branch_id'             => $value_assessment->branch_id,
                        'round_id'              => $value_assessment->round_id,
                        'checker_name'          => $value_assessment->checker_name,
                        'verifikator_1_name'    => $value_assessment->verifikator_1_name,
                        'verifikator_2_name'    => $value_assessment->verifikator_2_name,
                        'direg_name'            => $value_assessment->direg_name,
                        'dirop_name'            => $value_assessment->dirop_name,
                        'validator_name'        => $value_assessment->validator_name,
                        'status'                => 'Belum Berjalan',
                        'data_status'           => '0',
                        'created_by'            => 'System',
                        'created_date'          => date('Y-m-d H:i:s')
                    ];
                    TriggerNodeModel::insertAssignment($data);
                }
                $rs_assessment_detail = TriggerNodeModel::getBranchAssessmentDetail($value_assessment->id);
                foreach ($rs_assessment_detail as $key_detail => $value_detail) {
                    // $branch_assignment_id = DB::getPdo()->lastInsertId();
                    $branch_assignment_id = TriggerNodeModel::getAssignmentId($value_assessment->id);
                    // dd($branch_assignment_id);
                    $check_assignment_detail = TriggerNodeModel::checkAssignmentDetail($value_detail->id);
                    if (count($check_assignment_detail) < 1) {
                        $data = [
                            'assessment_detail_id'      => $value_detail->id,
                            'branch_assignment_id'      => $branch_assignment_id->id,
                            'branch_items_id'           => $value_detail->branch_items_id,
                            'assessment_component_id'   => $value_detail->assessment_component_id,
                            'status'                    => 'Belum Dikerjakan',
                            'created_by'                => 'System',
                            'created_date'              => date('Y-m-d H:i:s')
                        ];
                        TriggerNodeModel::insertAssignmentDetail($data);
                    }
                }
                
                // array_push($arr, $data);
            }
        }
        // dd($arr);
    }
}
