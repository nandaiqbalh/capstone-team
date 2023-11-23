<?php

namespace App\Models\Admin;

use App\Models\Admin\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardModel extends BaseModel
{
    /**
     * SUPER ADMIN
     */


    // -------------------------------------------------------------------------------------
    /**
     * HOLDING OPERASIONAL
     */
    // rekapitulasi nilai min max median average
    public static function holdingOpRekapitulasiNilai() {
        // nilai tiap rs
        $rs_branch_nilai = DashboardModel::holdingOpRekapitulasiNilaiTiapRs();

        // list bulan
        $rs_bulan = parent::bulanIndo();

        // hitung nilai min, max, median, average
        $final_data = [];
        foreach ($rs_bulan as $key => $value) {
            // hitung menggunakan fungsi bawaan collection

            // format tiap bulan
            // $final_data[$value] = [
            //     $rs_branch_nilai->min($value),
            //     $rs_branch_nilai->max($value),
            //     $rs_branch_nilai->median($value),
            //     $rs_branch_nilai->average($value)
            // ] ;

            // format tiap fungsi min, max, median, average
            // min
            // cek key ada atau tidak
            if(array_key_exists('min', $final_data)) {
                // jika sudah ada
                array_push($final_data['min'],round(floatval($rs_branch_nilai->min($value)),2));
            }
            else {
                // jika belum ada
                $final_data['min'] = [
                    round(floatval($rs_branch_nilai->min($value)),2)
                ];
            }

            // max
            // cek key ada atau tidak
            if(array_key_exists('max', $final_data)) {
                // jika sudah ada
                array_push($final_data['max'],round(floatval($rs_branch_nilai->max($value)),2));
            }
            else {
                // jika belum ada
                $final_data['max'] = [
                    round(floatval($rs_branch_nilai->max($value)),2)
                ];
            }

            // median
            // cek key ada atau tidak
            if(array_key_exists('median', $final_data)) {
                // jika sudah ada
                array_push($final_data['median'],round(floatval($rs_branch_nilai->median($value)),2));
            }
            else {
                // jika belum ada
                $final_data['median'] = [
                    round(floatval($rs_branch_nilai->median($value)),2)
                ];
            }

            // average
            // cek key ada atau tidak
            if(array_key_exists('average', $final_data)) {
                // jika sudah ada
                array_push($final_data['average'],round(floatval($rs_branch_nilai->average($value)),2));
            }
            else {
                // jika belum ada
                $final_data['average'] = [
                    round(floatval($rs_branch_nilai->average($value)),2)
                ];
            }
        };

        return $final_data;
    }

    // rekapitulasi tiap rumah sakit tiap bulan
    public static function holdingOpRekapitulasiNilaiTiapRs() {
        // list branch
        $rs_branch = DashboardModel::getListBranch('');
        // list bulan
        $rs_bulan = parent::bulanIndo();

        // perhitungan nilai tiap rumah sakit
        // A = 1
        // B = 0.5
        // C = 0.25
        $rs_branch_nilai = $rs_branch->each(function($item, $key) use($rs_bulan){

            // total komponen
            $rs_total = DashboardModel::holdingOpTotalKomponenSetiapBulanBy($item->id, date('Y'));
            // total scor A B C
            $score_a = DashboardModel::holdingOpTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'A');
            $score_b = DashboardModel::holdingOpTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'B');
            $score_c = DashboardModel::holdingOpTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'C');
            $score_belum_dinilai = DashboardModel::holdingOpTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),NULL);

            // tambah key dan nilai persen abrt-rl
            foreach ($rs_bulan as $index => $value) {
                // jika $rs_total[$index]
                if($rs_total[$index] > 0) {
                    $item->{$value} = ((($score_a[$index]*1)+($score_b[$index]*0.5)+($score_c[$index]*0.25)+($score_belum_dinilai[$index]*0))/$rs_total[$index])*100;
                }
                else {
                    // jika $rs_total[$index] == 0
                    $item->{$value} = 0;
                }
            }

            // return new item
            return $item;
        });

        return $rs_branch_nilai;
    }

    // rekapitulasi 3 nilai tertinggi & terendah
    public static function holdingOpRekapitulasiNilai3TeringgiTerendah() {
        // nilai tiap rs
        $rs_branch_nilai = DashboardModel::holdingOpRekapitulasiNilaiTiapRs();

        // jika tidak ada rs
        // berikan nilai default
        if($rs_branch_nilai->count() < 1) {
            return [];
        }

        // list bulan
        $rs_bulan = parent::bulanIndo();

        // ----------------------------------------------------------------------------
        // kelmpokkan nilai rumah sakit berdasarkan bulan
        $data_nilai = [];
        foreach ($rs_branch_nilai as $key => $branch_nilai) {

            // foreach bulan
            foreach ($rs_bulan as $key2 => $bulan) {
                // cek key ada atau tidak
                if(array_key_exists($bulan, $data_nilai)) {
                    // jika sudah ada
                    $data_nilai[$bulan][$branch_nilai->branch_name] = round($branch_nilai->{$bulan},2);
                }
                else {
                    // jika belum ada
                    $data_nilai[$bulan] = [
                        $branch_nilai->branch_name => round($branch_nilai->{$bulan},2)
                    ];
                }
            }

        }

        // ----------------------------------------------------------------------------
        // ambil 3 nilai tertinggi & 3 nilai terendah
        // dan buat format data array sesuai yang dibutuhkan chart
        $data= [];
        // foreach bulan
        foreach ($rs_bulan as $key => $bulan) {
            // urutkan dari paling besar
            arsort($data_nilai[$bulan]);

            // data tertinggi
            $arr_nilai_tertinggi = array_slice($data_nilai[$bulan],0,3);

            // tertinggi 1
            if(array_key_exists('tertinggi_1', $data)) {
                // jika sudah ada
                array_push($data['tertinggi_1'], array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[0] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[0] ?? 0
                ));
            }
            else {
                $data['tertinggi_1'] = [array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[0] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[0] ?? 0
                )];
            }

            // tertinggi 2
            if(array_key_exists('tertinggi_2', $data)) {
                // jika sudah ada
                array_push($data['tertinggi_2'], array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[1] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[1] ?? 0
                ));
            }
            else {
                $data['tertinggi_2'] = [array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[1] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[1] ?? 0
                )];
            }

            // tertinggi 3
            if(array_key_exists('tertinggi_3', $data)) {
                // jika sudah ada
                array_push($data['tertinggi_3'], array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[2] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[2] ?? 0
                ));
            }
            else {
                $data['tertinggi_3'] = [array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[2] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[2] ?? 0
                )];
            }

            // -----------------------------------------------------------------------
            // data terendah
            // data tertinggi
            $arr_nilai_terendah = array_slice($data_nilai[$bulan],-3,3);

            // tertinggi 1
            if(array_key_exists('terendah_1', $data)) {
                // jika sudah ada
                array_push($data['terendah_1'], array(
                    'branch'=> array_keys($arr_nilai_terendah)[0] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[0] ?? 0
                ));
            }
            else {
                $data['terendah_1'] = [array(
                    'branch'=> array_keys($arr_nilai_terendah)[0] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[0] ?? 0
                )];
            }

            // tertinggi 2
            if(array_key_exists('terendah_2', $data)) {
                // jika sudah ada
                array_push($data['terendah_2'], array(
                    'branch'=> array_keys($arr_nilai_terendah)[1] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[1] ?? 0
                ));
            }
            else {
                $data['terendah_2'] = [array(
                    'branch'=> array_keys($arr_nilai_terendah)[1] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[1] ?? 0
                )];
            }

            // tertinggi 3
            if(array_key_exists('terendah_3', $data)) {
                // jika sudah ada
                array_push($data['terendah_3'], array(
                    'branch'=> array_keys($arr_nilai_terendah)[2] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[2] ?? 0
                ));
            }
            else {
                $data['terendah_3'] = [array(
                    'branch'=> array_keys($arr_nilai_terendah)[2] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[2] ?? 0
                )];
            }
        }

        return $data;
    }

    // get total komponen penilaian by branch, month, year
    public static function holdingOpTotalKomponenSetiapBulanBy($branch_id,$year) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$key] = DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id',$branch_id)
                ->whereMonth('b.created_date', $key)
                ->whereYear('b.created_date', $year)
                ->where('b.data_status', '1')
                ->where('b.status', 'selesai')
                ->count('a.assessment_component_id');
        }

        return $data;
    }

    // get total komponen pembersihan by branch, month, year
    public static function holdingOpTotalKomponenScoreSetiapBulanBy($branch_id,$year, $score) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$key] = DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id',$branch_id)
                ->whereMonth('b.created_date', $key)
                ->whereYear('b.created_date', $year)
                ->where('b.data_status', '1')
                ->where('b.status', 'selesai')
                ->where('a.score', $score)
                ->count('a.assessment_component_id');
        }

        return $data;
    }

    // rekapitulasi parameter
    public static function holdingOpRekapitulasiParameter() {
        $year = date('Y');
        // rs branch
        $rs_branch = DashboardModel::getListBranch('');

        // --------------------------------------------------------------
        // hitung parameter tiap rs dalam setahun
        $data = [];
        foreach ($rs_branch as $key => $value) {
            // parameter
            $total_aman = DashboardModel::holdingOpGetTotalKomponenParameterBy($value->id, $year,'Aman');
            $total_bersih = DashboardModel::holdingOpGetTotalKomponenParameterBy($value->id, $year,'Bersih');
            $total_rapih = DashboardModel::holdingOpGetTotalKomponenParameterBy($value->id, $year,'Rapih');
            $total_tampak_baru = DashboardModel::holdingOpGetTotalKomponenParameterBy($value->id, $year,'Tampak Baru');
            $total_ramah_lingkungan = DashboardModel::holdingOpGetTotalKomponenParameterBy($value->id, $year,'Ramah Lingkungan');
            $total_tidak_aman = DashboardModel::holdingOpGetTotalKomponenParameterBy($value->id, $year,'Tidak Aman');
            $total_tidak_bersih = DashboardModel::holdingOpGetTotalKomponenParameterBy($value->id, $year,'Tidak Bersih');
            $total_tidak_rapih = DashboardModel::holdingOpGetTotalKomponenParameterBy($value->id, $year,'Tidak Rapih');
            $total_tidak_tampak_baru = DashboardModel::holdingOpGetTotalKomponenParameterBy($value->id, $year,'Tidak Tampak Baru');
            $total_tidak_ramah_lingkungan = DashboardModel::holdingOpGetTotalKomponenParameterBy($value->id, $year,'Tidak Ramah Lingkungan');

            // masukan ke dalam data penampung
            $data[$key] = [
                'branch_name'=> $value->branch_name,
                'persen_aman' => ($total_aman == 0) ? 0 : ($total_aman/($total_aman+$total_tidak_aman))*100,
                'persen_bersih' => ($total_bersih == 0) ? 0 : ($total_bersih/($total_bersih+$total_tidak_bersih))*100,
                'persen_rapih' => ($total_rapih == 0) ? 0 : ($total_rapih/($total_rapih+$total_tidak_rapih))*100,
                'persen_tampak_baru' => ($total_tampak_baru == 0) ? 0 : ($total_tampak_baru/($total_tampak_baru+$total_tidak_tampak_baru))*100,
                'persen_ramah_lingkungan' => ($total_ramah_lingkungan == 0) ? 0 : ($total_ramah_lingkungan/($total_ramah_lingkungan+$total_tidak_ramah_lingkungan))*100,
            ];

        };

        // ubah ke collection
        $rs_parameter = collect($data);

        // --------------------------------------------------------------
        // hitung min,max,median,average tiap bulan seluruh rs
        // dd($rs_parameter['08']->max('persen_aman'));
        $final_data = [];
        $final_data['min'] = [
            round(floatval($rs_parameter->min('persen_aman')),2),
            round(floatval($rs_parameter->min('persen_bersih')),2),
            round(floatval($rs_parameter->min('persen_rapih')),2),
            round(floatval($rs_parameter->min('persen_tampak_baru')),2),
            round(floatval($rs_parameter->min('persen_ramah_lingkungan')),2),
        ];

        $final_data['max'] = [
            round(floatval($rs_parameter->max('persen_aman')),2),
            round(floatval($rs_parameter->max('persen_bersih')),2),
            round(floatval($rs_parameter->max('persen_rapih')),2),
            round(floatval($rs_parameter->max('persen_tampak_baru')),2),
            round(floatval($rs_parameter->max('persen_ramah_lingkungan')),2),
        ];

        $final_data['median'] = [
            round(floatval($rs_parameter->median('persen_aman')),2),
            round(floatval($rs_parameter->median('persen_bersih')),2),
            round(floatval($rs_parameter->median('persen_rapih')),2),
            round(floatval($rs_parameter->median('persen_tampak_baru')),2),
            round(floatval($rs_parameter->median('persen_ramah_lingkungan')),2),
        ];

        $final_data['average'] = [
            round(floatval($rs_parameter->average('persen_aman')),2),
            round(floatval($rs_parameter->average('persen_bersih')),2),
            round(floatval($rs_parameter->average('persen_rapih')),2),
            round(floatval($rs_parameter->average('persen_tampak_baru')),2),
            round(floatval($rs_parameter->average('persen_ramah_lingkungan')),2),
        ];

        return $final_data;
    }

    // get total komponen parameter
    public static function holdingOpGetTotalKomponenParameterBy($branch_id,  $year, $parameter) {
        return DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id', $branch_id)
                // ->whereMonth('b.created_date', $month)
                ->whereYear('b.created_date', $year)
                ->where('a.parameter', $parameter)
                ->where('b.data_status','1')
                ->where('b.status', 'selesai')
                ->count('a.parameter');
    }

    // get target rata-rata nilai
    public static function holdingOpGetTargetRataNilai() {
        return DB::table('app_supports')->where('key','target_rata_rata_nilai')->value('value');
    }

    // get rekapitulasi hasil pekerjaan perbaikan
    public static function holdingOpGetHasilPekerjaanPerbaikan() {
        // list branch
        $rs_branch = DashboardModel::getListBranch('');
        // list bulan
        $rs_bulan = parent::bulanIndo();

        $year = date('Y');

        // perhitungan
        $rs_perbaikan = $rs_branch->each(function($item, $key) use($rs_bulan,$year){

            // total komponen
            $rs_total_komponen = DashboardModel::holdingOpTotalKomponenSetiapBulanBy($item->id, $year);
            // total scor B
            $rs_total_perbaikan = DashboardModel::holdingOpTotalKomponenScoreSetiapBulanBy($item->id, $year,'B');

            // tambah key dan nilai persen abrt-rl
            foreach ($rs_bulan as $index => $value) {
                // jika $rs_total[$index]
                if($rs_total_komponen[$index] > 0) {
                    $item->{$value} = ($rs_total_perbaikan[$index]/$rs_total_komponen[$index])*100;
                }
                else {
                    // jika $rs_total[$index] == 0
                    $item->{$value} = 0;
                }
            }

            // return new item
            return $item;
        });

        // hitung nilai min, max, median, average
        $final_data = [];
        foreach ($rs_bulan as $key => $value) {
            // hitung menggunakan fungsi bawaan collection
            // format tiap fungsi min, max, median, average
            // min
            // cek key ada atau tidak
            if(array_key_exists('min', $final_data)) {
                // jika sudah ada
                array_push($final_data['min'],round(floatval($rs_perbaikan->min($value)),2));
            }
            else {
                // jika belum ada
                $final_data['min'] = [
                    round(floatval($rs_perbaikan->min($value)),2)
                ];
            }

            // max
            // cek key ada atau tidak
            if(array_key_exists('max', $final_data)) {
                // jika sudah ada
                array_push($final_data['max'],round(floatval($rs_perbaikan->max($value)),2));
            }
            else {
                // jika belum ada
                $final_data['max'] = [
                    round(floatval($rs_perbaikan->max($value)),2)
                ];
            }

            // median
            // cek key ada atau tidak
            if(array_key_exists('median', $final_data)) {
                // jika sudah ada
                array_push($final_data['median'],round(floatval($rs_perbaikan->median($value)),2));
            }
            else {
                // jika belum ada
                $final_data['median'] = [
                    round(floatval($rs_perbaikan->median($value)),2)
                ];
            }

            // average
            // cek key ada atau tidak
            if(array_key_exists('average', $final_data)) {
                // jika sudah ada
                array_push($final_data['average'],round(floatval($rs_perbaikan->average($value)),2));
            }
            else {
                // jika belum ada
                $final_data['average'] = [
                    round(floatval($rs_perbaikan->average($value)),2)
                ];
            }
        };

        return $final_data;
    }

    // get rekapitulasi hasil pekerjaan perbaikan
    public static function holdingOpGetHasilPekerjaanPenggantian() {
        // list branch
        $rs_branch = DashboardModel::getListBranch('');
        // list bulan
        $rs_bulan = parent::bulanIndo();

        $year = date('Y');

        // perhitungan
        $rs_perbaikan = $rs_branch->each(function($item, $key) use($rs_bulan,$year){

            // total komponen
            $rs_total_komponen = DashboardModel::holdingOpTotalKomponenSetiapBulanBy($item->id, $year);
            // total scor B
            $rs_total_penggantian = DashboardModel::holdingOpTotalKomponenScoreSetiapBulanBy($item->id, $year,'C');

            // tambah key dan nilai persen abrt-rl
            foreach ($rs_bulan as $index => $value) {
                // jika $rs_total[$index]
                if($rs_total_komponen[$index] > 0) {
                    $item->{$value} = ($rs_total_penggantian[$index]/$rs_total_komponen[$index])*100;
                }
                else {
                    // jika $rs_total[$index] == 0
                    $item->{$value} = 0;
                }
            }

            // return new item
            return $item;
        });

        // hitung nilai min, max, median, average
        $final_data = [];
        foreach ($rs_bulan as $key => $value) {
            // hitung menggunakan fungsi bawaan collection
            // format tiap fungsi min, max, median, average
            // min
            // cek key ada atau tidak
            if(array_key_exists('min', $final_data)) {
                // jika sudah ada
                array_push($final_data['min'],round(floatval($rs_perbaikan->min($value)),2));
            }
            else {
                // jika belum ada
                $final_data['min'] = [
                    round(floatval($rs_perbaikan->min($value)),2)
                ];
            }

            // max
            // cek key ada atau tidak
            if(array_key_exists('max', $final_data)) {
                // jika sudah ada
                array_push($final_data['max'],round(floatval($rs_perbaikan->max($value)),2));
            }
            else {
                // jika belum ada
                $final_data['max'] = [
                    round(floatval($rs_perbaikan->max($value)),2)
                ];
            }

            // median
            // cek key ada atau tidak
            if(array_key_exists('median', $final_data)) {
                // jika sudah ada
                array_push($final_data['median'],round(floatval($rs_perbaikan->median($value)),2));
            }
            else {
                // jika belum ada
                $final_data['median'] = [
                    round(floatval($rs_perbaikan->median($value)),2)
                ];
            }

            // average
            // cek key ada atau tidak
            if(array_key_exists('average', $final_data)) {
                // jika sudah ada
                array_push($final_data['average'],round(floatval($rs_perbaikan->average($value)),2));
            }
            else {
                // jika belum ada
                $final_data['average'] = [
                    round(floatval($rs_perbaikan->average($value)),2)
                ];
            }
        };

        return $final_data;
    }

    // get data
    public static function holdingOpGetTerlambatSubmit() {
        // list ronde
        $rs_ronde = DashboardModel::holdingOpGetListRonde();
        $year = date('Y');

        // perhitungan
        $final_data = [
            'round_1'=> [],
            'round_2'=> [],
            'round_3'=> [],
            'round_4'=> []
        ];

        foreach ($rs_ronde as $key1 => $item) {
            # code...
            $arr_terlambat_submit = DashboardModel::holdingOpGetListTerlambatSubmitSemuaBulan($item->id, $year);
            foreach ($arr_terlambat_submit as $key => $value) {
                if($item->id == 1) {
                    array_push($final_data['round_1'],$value );
                }

                if($item->id == 2) {
                    array_push($final_data['round_2'],$value );
                }

                if($item->id == 3) {
                    array_push($final_data['round_3'],$value );
                }

                if($item->id == 4) {
                    array_push($final_data['round_4'],$value );
                }
            }
        }

        return $final_data;
    }

    // get submit terlambat bulanan
    public static function holdingOpGetListTerlambatSubmitSemuaBulan($round_id, $year ) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$value] =  DB::table('branch_assessment')
                ->where('round_id', $round_id)
                ->whereNotNull('verifikator_2_approved_by_system')
                ->whereMonth('created_date', $key)
                ->whereYear('created_date', $year)
                ->where('status','selesai')
                ->where('data_status','1')
                ->count('verifikator_2_approved_by_system');
        }
        return $data;
    }

    // list ronde
    public static function holdingOpGetListRonde() {
        return DB::table('master_round')
            ->select('id','name as round_name')
            ->where('data_status','1')
            ->orderBy('name','asc')
            ->get();
    }

    // -------------------------------------------------------------------------------------
    /**
     * HOLDING REGIONAL
     */
    // rekapitulasi nilai min max median average
    public static function holdingRgRekapitulasiNilai() {

        // nilai tiap rs
        $rs_branch_nilai = DashboardModel::holdingRgRekapitulasiNilaiTiapRs();

        // list bulan
        $rs_bulan = parent::bulanIndo();

        // hitung nilai min, max, median, average
        $final_data = [];
        foreach ($rs_bulan as $key => $value) {
            // hitung menggunakan fungsi bawaan collection

            // format tiap fungsi min, max, median, average
            // min
            // cek key ada atau tidak
            if(array_key_exists('min', $final_data)) {
                // jika sudah ada
                array_push($final_data['min'],round(floatval($rs_branch_nilai->min($value)),2));
            }
            else {
                // jika belum ada
                $final_data['min'] = [
                    round(floatval($rs_branch_nilai->min($value)),2)
                ];
            }

            // max
            // cek key ada atau tidak
            if(array_key_exists('max', $final_data)) {
                // jika sudah ada
                array_push($final_data['max'],round(floatval($rs_branch_nilai->max($value)),2));
            }
            else {
                // jika belum ada
                $final_data['max'] = [
                    round(floatval($rs_branch_nilai->max($value)),2)
                ];
            }

            // median
            // cek key ada atau tidak
            if(array_key_exists('median', $final_data)) {
                // jika sudah ada
                array_push($final_data['median'],round(floatval($rs_branch_nilai->median($value)),2));
            }
            else {
                // jika belum ada
                $final_data['median'] = [
                    round(floatval($rs_branch_nilai->median($value)),2)
                ];
            }

            // average
            // cek key ada atau tidak
            if(array_key_exists('average', $final_data)) {
                // jika sudah ada
                array_push($final_data['average'],round(floatval($rs_branch_nilai->average($value)),2));
            }
            else {
                // jika belum ada
                $final_data['average'] = [
                    round(floatval($rs_branch_nilai->average($value)),2)
                ];
            }
        };

        return $final_data;
    }

    // rekapitulasi tiap rumah sakit tiap bulan
    public static function holdingRgRekapitulasiNilaiTiapRs() {

        // list branch
        $rs_branch = DashboardModel::getListBranchByRegional('Regional '.Auth::user()->region_id);

        // list bulan
        $rs_bulan = parent::bulanIndo();

        // perhitungan nilai tiap rumah sakit
        // A = 1
        // B = 0.5
        // C = 0.25
        $rs_branch_nilai = $rs_branch->each(function($item, $key) use($rs_bulan){

            // total komponen
            $rs_total = DashboardModel::holdingRgTotalKomponenSetiapBulanBy($item->id, date('Y'));
            // total scor A B C
            $score_a = DashboardModel::holdingRgTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'A');
            $score_b = DashboardModel::holdingRgTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'B');
            $score_c = DashboardModel::holdingRgTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'C');
            $score_belum_dinilai = DashboardModel::holdingRgTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),NULL);

            // tambah key dan nilai persen abrt-rl
            foreach ($rs_bulan as $index => $value) {
                // jika $rs_total[$index]
                if($rs_total[$index] > 0) {
                    $item->{$value} = ((($score_a[$index]*1)+($score_b[$index]*0.5)+($score_c[$index]*0.25)+($score_belum_dinilai[$index]*0))/$rs_total[$index])*100;
                }
                else {
                    // jika $rs_total[$index] == 0
                    $item->{$value} = 0;
                }
            }

            // return new item
            return $item;
        });

        return $rs_branch_nilai;
    }

    // rekapitulasi 3 nilai tertinggi & terendah
    public static function holdingRgRekapitulasiNilai3TeringgiTerendah() {
        // nilai tiap rs
        $rs_branch_nilai = DashboardModel::holdingRgRekapitulasiNilaiTiapRs();
        // jika tidak ada rs
        // berikan nilai default
        if($rs_branch_nilai->count() < 1) {
            return [];
        }

        // list bulan
        $rs_bulan = parent::bulanIndo();

        // ----------------------------------------------------------------------------
        // kelmpokkan nilai rumah sakit berdasarkan bulan
        $data_nilai = [];
        foreach ($rs_branch_nilai as $key => $branch_nilai) {

            // foreach bulan
            foreach ($rs_bulan as $key2 => $bulan) {
                // cek key ada atau tidak
                if(array_key_exists($bulan, $data_nilai)) {
                    // jika sudah ada
                    $data_nilai[$bulan][$branch_nilai->branch_name] = round($branch_nilai->{$bulan},2);
                }
                else {
                    // jika belum ada
                    $data_nilai[$bulan] = [
                        $branch_nilai->branch_name => round($branch_nilai->{$bulan},2)
                    ];
                }
            }

        }

        // ----------------------------------------------------------------------------
        // ambil 3 nilai tertinggi & 3 nilai terendah
        // dan buat format data array sesuai yang dibutuhkan chart
        $data= [];
        // foreach bulan
        foreach ($rs_bulan as $key => $bulan) {
            // urutkan dari paling besar
            arsort($data_nilai[$bulan]);

            // data tertinggi
            $arr_nilai_tertinggi = array_slice($data_nilai[$bulan],0,3);

            // tertinggi 1
            if(array_key_exists('tertinggi_1', $data)) {
                // jika sudah ada
                array_push($data['tertinggi_1'], array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[0] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[0] ?? 0
                ));
            }
            else {
                $data['tertinggi_1'] = [array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[0] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[0] ?? 0
                )];
            }

            // tertinggi 2
            if(array_key_exists('tertinggi_2', $data)) {
                // jika sudah ada
                array_push($data['tertinggi_2'], array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[1] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[1] ?? 0
                ));
            }
            else {
                $data['tertinggi_2'] = [array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[1] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[1] ?? 0
                )];
            }

            // tertinggi 3
            if(array_key_exists('tertinggi_3', $data)) {
                // jika sudah ada
                array_push($data['tertinggi_3'], array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[2] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[2] ?? 0
                ));
            }
            else {
                $data['tertinggi_3'] = [array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[2] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[2] ?? 0
                )];
            }

            // -----------------------------------------------------------------------
            // data terendah
            // data tertinggi
            $arr_nilai_terendah = array_slice($data_nilai[$bulan],-3,3);

            // tertinggi 1
            if(array_key_exists('terendah_1', $data)) {
                // jika sudah ada
                array_push($data['terendah_1'], array(
                    'branch'=> array_keys($arr_nilai_terendah)[0] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[0] ?? 0
                ));
            }
            else {
                $data['terendah_1'] = [array(
                    'branch'=> array_keys($arr_nilai_terendah)[0] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[0] ?? 0
                )];
            }

            // tertinggi 2
            if(array_key_exists('terendah_2', $data)) {
                // jika sudah ada
                array_push($data['terendah_2'], array(
                    'branch'=> array_keys($arr_nilai_terendah)[1] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[1] ?? 0
                ));
            }
            else {
                $data['terendah_2'] = [array(
                    'branch'=> array_keys($arr_nilai_terendah)[1] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[1] ?? 0
                )];
            }

            // tertinggi 3
            if(array_key_exists('terendah_3', $data)) {
                // jika sudah ada
                array_push($data['terendah_3'], array(
                    'branch'=> array_keys($arr_nilai_terendah)[2] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[2] ?? 0
                ));
            }
            else {
                $data['terendah_3'] = [array(
                    'branch'=> array_keys($arr_nilai_terendah)[2] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[2] ?? 0
                )];
            }
        }

        return $data;
    }

    // get total komponen penilaian by branch, month, year
    public static function holdingRgTotalKomponenSetiapBulanBy($branch_id,$year) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$key] = DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id',$branch_id)
                ->whereMonth('b.created_date', $key)
                ->whereYear('b.created_date', $year)
                ->where('b.data_status', '1')
                ->where('b.status', 'selesai')
                ->count('a.assessment_component_id');
        }

        return $data;
    }

    // get total komponen pembersihan by branch, month, year
    public static function holdingRgTotalKomponenScoreSetiapBulanBy($branch_id,$year, $score) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$key] = DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id',$branch_id)
                ->whereMonth('b.created_date', $key)
                ->whereYear('b.created_date', $year)
                ->where('b.data_status', '1')
                ->where('b.status', 'selesai')
                ->where('a.score', $score)
                ->count('a.assessment_component_id');
        }

        return $data;
    }

    // rekapitulasi parameter
    public static function holdingRgRekapitulasiParameter() {
        $year = date('Y');
        // rs branch
        $rs_branch = DashboardModel::getListBranchByRegional('Regional '.Auth::user()->region_id);

        // --------------------------------------------------------------
        // hitung parameter tiap rs dalam setahun
        $data = [];
        foreach ($rs_branch as $key => $value) {
            // parameter
            $total_aman = DashboardModel::holdingRgGetTotalKomponenParameterBy($value->id, $year,'Aman');
            $total_bersih = DashboardModel::holdingRgGetTotalKomponenParameterBy($value->id, $year,'Bersih');
            $total_rapih = DashboardModel::holdingRgGetTotalKomponenParameterBy($value->id, $year,'Rapih');
            $total_tampak_baru = DashboardModel::holdingRgGetTotalKomponenParameterBy($value->id, $year,'Tampak Baru');
            $total_ramah_lingkungan = DashboardModel::holdingRgGetTotalKomponenParameterBy($value->id, $year,'Ramah Lingkungan');
            $total_tidak_aman = DashboardModel::holdingRgGetTotalKomponenParameterBy($value->id, $year,'Tidak Aman');
            $total_tidak_bersih = DashboardModel::holdingRgGetTotalKomponenParameterBy($value->id, $year,'Tidak Bersih');
            $total_tidak_rapih = DashboardModel::holdingRgGetTotalKomponenParameterBy($value->id, $year,'Tidak Rapih');
            $total_tidak_tampak_baru = DashboardModel::holdingRgGetTotalKomponenParameterBy($value->id, $year,'Tidak Tampak Baru');
            $total_tidak_ramah_lingkungan = DashboardModel::holdingRgGetTotalKomponenParameterBy($value->id, $year,'Tidak Ramah Lingkungan');

            // masukan ke dalam data penampung
            $data[$key] = [
                'branch_name'=> $value->branch_name,
                'persen_aman' => ($total_aman == 0) ? 0 : ($total_aman/($total_aman+$total_tidak_aman))*100,
                'persen_bersih' => ($total_bersih == 0) ? 0 : ($total_bersih/($total_bersih+$total_tidak_bersih))*100,
                'persen_rapih' => ($total_rapih == 0) ? 0 : ($total_rapih/($total_rapih+$total_tidak_rapih))*100,
                'persen_tampak_baru' => ($total_tampak_baru == 0) ? 0 : ($total_tampak_baru/($total_tampak_baru+$total_tidak_tampak_baru))*100,
                'persen_ramah_lingkungan' => ($total_ramah_lingkungan == 0) ? 0 : ($total_ramah_lingkungan/($total_ramah_lingkungan+$total_tidak_ramah_lingkungan))*100,
            ];

        };

        // ubah ke collection
        $rs_parameter = collect($data);

        // --------------------------------------------------------------
        // hitung min,max,median,average tiap bulan seluruh rs
        // dd($rs_parameter['08']->max('persen_aman'));
        $final_data = [];
        $final_data['min'] = [
            round(floatval($rs_parameter->min('persen_aman')),2),
            round(floatval($rs_parameter->min('persen_bersih')),2),
            round(floatval($rs_parameter->min('persen_rapih')),2),
            round(floatval($rs_parameter->min('persen_tampak_baru')),2),
            round(floatval($rs_parameter->min('persen_ramah_lingkungan')),2),
        ];

        $final_data['max'] = [
            round(floatval($rs_parameter->max('persen_aman')),2),
            round(floatval($rs_parameter->max('persen_bersih')),2),
            round(floatval($rs_parameter->max('persen_rapih')),2),
            round(floatval($rs_parameter->max('persen_tampak_baru')),2),
            round(floatval($rs_parameter->max('persen_ramah_lingkungan')),2),
        ];

        $final_data['median'] = [
            round(floatval($rs_parameter->median('persen_aman')),2),
            round(floatval($rs_parameter->median('persen_bersih')),2),
            round(floatval($rs_parameter->median('persen_rapih')),2),
            round(floatval($rs_parameter->median('persen_tampak_baru')),2),
            round(floatval($rs_parameter->median('persen_ramah_lingkungan')),2),
        ];

        $final_data['average'] = [
            round(floatval($rs_parameter->average('persen_aman')),2),
            round(floatval($rs_parameter->average('persen_bersih')),2),
            round(floatval($rs_parameter->average('persen_rapih')),2),
            round(floatval($rs_parameter->average('persen_tampak_baru')),2),
            round(floatval($rs_parameter->average('persen_ramah_lingkungan')),2),
        ];

        return $final_data;
    }

    // get total komponen parameter
    public static function holdingRgGetTotalKomponenParameterBy($branch_id,  $year, $parameter) {
        return DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id', $branch_id)
                // ->whereMonth('b.created_date', $month)
                ->whereYear('b.created_date', $year)
                ->where('a.parameter', $parameter)
                ->where('b.data_status','1')
                ->where('b.status', 'selesai')
                ->count('a.parameter');
    }

    // get target rata-rata nilai
    public static function holdingRgGetTargetRataNilai() {
        return DB::table('app_supports')->where('key','target_rata_rata_nilai')->value('value');
    }

    // get rekapitulasi hasil pekerjaan perbaikan
    public static function holdingRgGetHasilPekerjaanPerbaikan() {
        // list branch
        $rs_branch = DashboardModel::getListBranchByRegional('Regional '.Auth::user()->region_id);
        // list bulan
        $rs_bulan = parent::bulanIndo();

        $year = date('Y');

        // perhitungan
        $rs_perbaikan = $rs_branch->each(function($item, $key) use($rs_bulan,$year){

            // total komponen
            $rs_total_komponen = DashboardModel::holdingRgTotalKomponenSetiapBulanBy($item->id, $year);
            // total scor B
            $rs_total_perbaikan = DashboardModel::holdingRgTotalKomponenScoreSetiapBulanBy($item->id, $year,'B');

            // tambah key dan nilai persen abrt-rl
            foreach ($rs_bulan as $index => $value) {
                // jika $rs_total[$index]
                if($rs_total_komponen[$index] > 0) {
                    $item->{$value} = ($rs_total_perbaikan[$index]/$rs_total_komponen[$index])*100;
                }
                else {
                    // jika $rs_total[$index] == 0
                    $item->{$value} = 0;
                }
            }

            // return new item
            return $item;
        });

        // hitung nilai min, max, median, average
        $final_data = [];
        foreach ($rs_bulan as $key => $value) {
            // hitung menggunakan fungsi bawaan collection
            // format tiap fungsi min, max, median, average
            // min
            // cek key ada atau tidak
            if(array_key_exists('min', $final_data)) {
                // jika sudah ada
                array_push($final_data['min'],round(floatval($rs_perbaikan->min($value)),2));
            }
            else {
                // jika belum ada
                $final_data['min'] = [
                    round(floatval($rs_perbaikan->min($value)),2)
                ];
            }

            // max
            // cek key ada atau tidak
            if(array_key_exists('max', $final_data)) {
                // jika sudah ada
                array_push($final_data['max'],round(floatval($rs_perbaikan->max($value)),2));
            }
            else {
                // jika belum ada
                $final_data['max'] = [
                    round(floatval($rs_perbaikan->max($value)),2)
                ];
            }

            // median
            // cek key ada atau tidak
            if(array_key_exists('median', $final_data)) {
                // jika sudah ada
                array_push($final_data['median'],round(floatval($rs_perbaikan->median($value)),2));
            }
            else {
                // jika belum ada
                $final_data['median'] = [
                    round(floatval($rs_perbaikan->median($value)),2)
                ];
            }

            // average
            // cek key ada atau tidak
            if(array_key_exists('average', $final_data)) {
                // jika sudah ada
                array_push($final_data['average'],round(floatval($rs_perbaikan->average($value)),2));
            }
            else {
                // jika belum ada
                $final_data['average'] = [
                    round(floatval($rs_perbaikan->average($value)),2)
                ];
            }
        };

        return $final_data;
    }

    // get rekapitulasi hasil pekerjaan perbaikan
    public static function holdingRgGetHasilPekerjaanPenggantian() {
        // list branch
        $rs_branch = DashboardModel::getListBranchByRegional('Regional '.Auth::user()->region_id);
        // list bulan
        $rs_bulan = parent::bulanIndo();

        $year = date('Y');

        // perhitungan
        $rs_perbaikan = $rs_branch->each(function($item, $key) use($rs_bulan,$year){

            // total komponen
            $rs_total_komponen = DashboardModel::holdingRgTotalKomponenSetiapBulanBy($item->id, $year);
            // total scor B
            $rs_total_penggantian = DashboardModel::holdingRgTotalKomponenScoreSetiapBulanBy($item->id, $year,'C');

            // tambah key dan nilai persen abrt-rl
            foreach ($rs_bulan as $index => $value) {
                // jika $rs_total[$index]
                if($rs_total_komponen[$index] > 0) {
                    $item->{$value} = ($rs_total_penggantian[$index]/$rs_total_komponen[$index])*100;
                }
                else {
                    // jika $rs_total[$index] == 0
                    $item->{$value} = 0;
                }
            }

            // return new item
            return $item;
        });

        // hitung nilai min, max, median, average
        $final_data = [];
        foreach ($rs_bulan as $key => $value) {
            // hitung menggunakan fungsi bawaan collection
            // format tiap fungsi min, max, median, average
            // min
            // cek key ada atau tidak
            if(array_key_exists('min', $final_data)) {
                // jika sudah ada
                array_push($final_data['min'],round(floatval($rs_perbaikan->min($value)),2));
            }
            else {
                // jika belum ada
                $final_data['min'] = [
                    round(floatval($rs_perbaikan->min($value)),2)
                ];
            }

            // max
            // cek key ada atau tidak
            if(array_key_exists('max', $final_data)) {
                // jika sudah ada
                array_push($final_data['max'],round(floatval($rs_perbaikan->max($value)),2));
            }
            else {
                // jika belum ada
                $final_data['max'] = [
                    round(floatval($rs_perbaikan->max($value)),2)
                ];
            }

            // median
            // cek key ada atau tidak
            if(array_key_exists('median', $final_data)) {
                // jika sudah ada
                array_push($final_data['median'],round(floatval($rs_perbaikan->median($value)),2));
            }
            else {
                // jika belum ada
                $final_data['median'] = [
                    round(floatval($rs_perbaikan->median($value)),2)
                ];
            }

            // average
            // cek key ada atau tidak
            if(array_key_exists('average', $final_data)) {
                // jika sudah ada
                array_push($final_data['average'],round(floatval($rs_perbaikan->average($value)),2));
            }
            else {
                // jika belum ada
                $final_data['average'] = [
                    round(floatval($rs_perbaikan->average($value)),2)
                ];
            }
        };

        return $final_data;
    }

    // get data
    public static function holdingRgGetTerlambatSubmit() {
        // list ronde
        $rs_ronde = DashboardModel::holdingRgGetListRonde();
        $year = date('Y');

        // perhitungan
        $final_data = [
            'round_1'=> [],
            'round_2'=> [],
            'round_3'=> [],
            'round_4'=> []
        ];

        foreach ($rs_ronde as $key1 => $item) {
            # code...
            $arr_terlambat_submit = DashboardModel::holdingRgGetListTerlambatSubmitSemuaBulan($item->id, $year);
            foreach ($arr_terlambat_submit as $key => $value) {
                if($item->id == 1) {
                    array_push($final_data['round_1'],$value );
                }

                if($item->id == 2) {
                    array_push($final_data['round_2'],$value );
                }

                if($item->id == 3) {
                    array_push($final_data['round_3'],$value );
                }

                if($item->id == 4) {
                    array_push($final_data['round_4'],$value );
                }
            }
        }

        return $final_data;
    }

    // // get submit terlambat bulanan
    // public static function holdingRgGetListTerlambatSubmitSemuaBulan($round_id, $year ) {
    //     $rs_bulan = parent::bulanIndo();

    //     $data = [];
    //     foreach ($rs_bulan as $key => $value) {
    //         $data[$value] =  DB::table('branch_assessment as a')
    //             ->join('master_branch as b','a.branch_id','=','b.id')
    //             ->where('a.round_id', $round_id)
    //             ->whereNotNull('a.verifikator_2_approved_by_system')
    //             ->whereMonth('a.created_date', $key)
    //             ->whereYear('a.created_date', $year)
    //             ->where('a.status','selesai')
    //             ->where('a.data_status','1')
    //             ->where('b.region_name', 'Regional '.Auth::user()->region_id)
    //             ->count('a.verifikator_2_approved_by_system');
    //     }
    //     return $data;
    // }

    // list ronde
    public static function holdingRgGetListRonde() {
        return DB::table('master_round')
            ->select('id','name as round_name')
            ->where('data_status','1')
            ->orderBy('name','asc')
            ->get();
    }

    // // get list branch
    // public static function getListBranchByRegional($region_name) {
    //     return DB::table('master_branch as a')
    //         ->select('a.id','a.name as branch_name')
    //         ->where('a.region_name', $region_name)
    //         ->where('a.data_status','1')
    //         ->orderBy('a.name','asc')
    //         ->get();
    // }

    public static function holdingRgTotalPekerjaanTiapBulanByTahunByScore($month, $year, $score){
        $rs_rumah_sakit = DashboardModel::getListBranchByRegional('Regional '.Auth::user()->region_id)->pluck('id');
        $arr_rumah_sakit = $rs_rumah_sakit->toArray();

        return DB::table('branch_assignment_detail as a')
                ->select(
                    DB::raw('COUNT(a.id) as jumlah_total'),
                    DB::raw('COUNT(CASE WHEN a.status = "Belum Dikerjakan" THEN 1 ELSE NULL END) as jumlah_belum_dikerjakan'),
                    DB::raw('COUNT(CASE WHEN a.status = "Selesai" THEN 1 ELSE NULL END) as jumlah_selesai')
                )
                ->join('branch_assignment as b','a.branch_assignment_id','=','b.id')
                ->join('branch_assessment_detail as c','a.assessment_detail_id','=','c.id')
                ->whereIn('b.branch_id', $arr_rumah_sakit)
                ->whereMonth('b.created_date', $month)
                ->whereYear('b.created_date', $year)
                ->where('c.score', $score)
                ->first();
    }

    // get data
    public static function holdingRgGetPekerjaanTerlambatPersetujuan() {
        // list ronde
        $rs_ronde = DashboardModel::validatorGetListRonde();
        $year = date('Y');

        // perhitungan
        $final_data = [
            'round_1'=> [],
            'round_2'=> [],
            'round_3'=> [],
            'round_4'=> []
        ];

        foreach ($rs_ronde as $key1 => $item) {
            # code...
            $arr_terlambat_submit = DashboardModel::holdingRgGetListPekerjaanTerlambatPersetujuanSemuaBulan($item->id, $year);
            foreach ($arr_terlambat_submit as $key => $value) {
                if($item->id == 1) {
                    array_push($final_data['round_1'],$value );
                }

                if($item->id == 2) {
                    array_push($final_data['round_2'],$value );
                }

                if($item->id == 3) {
                    array_push($final_data['round_3'],$value );
                }

                if($item->id == 4) {
                    array_push($final_data['round_4'],$value );
                }
            }
        }

        return $final_data;
    }

    // get submit terlambat bulanan
    public static function holdingRgGetListPekerjaanTerlambatPersetujuanSemuaBulan($round_id, $year ) {
        $rs_bulan = parent::bulanIndo();
        $rs_rumah_sakit = DashboardModel::getListBranchByRegional('Regional '.Auth::user()->region_id)->pluck('id');
        $arr_rumah_sakit = $rs_rumah_sakit->toArray();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$value] =  DB::table('branch_assignment')
                ->whereIn('branch_id', $arr_rumah_sakit)
                ->where('round_id', $round_id)
                ->whereNotNull('verifikator_2_approved_by_system')
                ->whereMonth('created_date', $key)
                ->whereYear('created_date', $year)
                ->where('status','selesai')
                ->where('data_status','1')
                ->count('verifikator_2_approved_by_system');
        }
        return $data;
    }


    // -------------------------------------------------------------------------------------
    /**
     * VALIDATOR
     */
    // rekapitulasi nilai min max median average
    public static function validatorRekapitulasiNilai() {
        // nilai tiap rs
        $rs_branch_nilai = DashboardModel::validatorRekapitulasiNilaiTiapRs();

        // list bulan
        $rs_bulan = parent::bulanIndo();

        // hitung nilai min, max, median, average
        $final_data = [];
        foreach ($rs_bulan as $key => $value) {
            // hitung menggunakan fungsi bawaan collection

            // format tiap bulan
            // $final_data[$value] = [
            //     $rs_branch_nilai->min($value),
            //     $rs_branch_nilai->max($value),
            //     $rs_branch_nilai->median($value),
            //     $rs_branch_nilai->average($value)
            // ] ;

            // format tiap fungsi min, max, median, average
            // min
            // cek key ada atau tidak
            if(array_key_exists('min', $final_data)) {
                // jika sudah ada
                array_push($final_data['min'],round(floatval($rs_branch_nilai->min($value)),2));
            }
            else {
                // jika belum ada
                $final_data['min'] = [
                    round(floatval($rs_branch_nilai->min($value)),2)
                ];
            }

            // max
            // cek key ada atau tidak
            if(array_key_exists('max', $final_data)) {
                // jika sudah ada
                array_push($final_data['max'],round(floatval($rs_branch_nilai->max($value)),2));
            }
            else {
                // jika belum ada
                $final_data['max'] = [
                    round(floatval($rs_branch_nilai->max($value)),2)
                ];
            }

            // median
            // cek key ada atau tidak
            if(array_key_exists('median', $final_data)) {
                // jika sudah ada
                array_push($final_data['median'],round(floatval($rs_branch_nilai->median($value)),2));
            }
            else {
                // jika belum ada
                $final_data['median'] = [
                    round(floatval($rs_branch_nilai->median($value)),2)
                ];
            }

            // average
            // cek key ada atau tidak
            if(array_key_exists('average', $final_data)) {
                // jika sudah ada
                array_push($final_data['average'],round(floatval($rs_branch_nilai->average($value)),2));
            }
            else {
                // jika belum ada
                $final_data['average'] = [
                    round(floatval($rs_branch_nilai->average($value)),2)
                ];
            }
        };

        return $final_data;
    }

    // rekapitulasi tiap rumah sakit tiap bulan
    public static function validatorRekapitulasiNilaiTiapRs() {
        // list branch
        $rs_branch = DashboardModel::getListBranch('');
        // list bulan
        $rs_bulan = parent::bulanIndo();

        // perhitungan nilai tiap rumah sakit
        // A = 1
        // B = 0.5
        // C = 0.25
        $rs_branch_nilai = $rs_branch->each(function($item, $key) use($rs_bulan){

            // total komponen
            $rs_total = DashboardModel::validatorTotalKomponenSetiapBulanBy($item->id, date('Y'));
            // total scor A B C
            $score_a = DashboardModel::validatorTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'A');
            $score_b = DashboardModel::validatorTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'B');
            $score_c = DashboardModel::validatorTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),'C');
            $score_belum_dinilai = DashboardModel::validatorTotalKomponenScoreSetiapBulanBy($item->id, date('Y'),NULL);

            // tambah key dan nilai persen abrt-rl
            foreach ($rs_bulan as $index => $value) {
                // jika $rs_total[$index]
                if($rs_total[$index] > 0) {
                    $item->{$value} = ((($score_a[$index]*1)+($score_b[$index]*0.5)+($score_c[$index]*0.25)+($score_belum_dinilai[$index]*0))/$rs_total[$index])*100;
                }
                else {
                    // jika $rs_total[$index] == 0
                    $item->{$value} = 0;
                }
            }

            // return new item
            return $item;
        });

        return $rs_branch_nilai;
    }

    // rekapitulasi 3 nilai tertinggi & terendah
    public static function validatorRekapitulasiNilai3TeringgiTerendah() {
        // nilai tiap rs
        $rs_branch_nilai = DashboardModel::validatorRekapitulasiNilaiTiapRs();

        // jika tidak ada rs
        // berikan nilai default
        if($rs_branch_nilai->count() < 1) {
            return [];
        }

        // list bulan
        $rs_bulan = parent::bulanIndo();

        // ----------------------------------------------------------------------------
        // kelmpokkan nilai rumah sakit berdasarkan bulan
        $data_nilai = [];
        foreach ($rs_branch_nilai as $key => $branch_nilai) {

            // foreach bulan
            foreach ($rs_bulan as $key2 => $bulan) {
                // cek key ada atau tidak
                if(array_key_exists($bulan, $data_nilai)) {
                    // jika sudah ada
                    $data_nilai[$bulan][$branch_nilai->branch_name] = round($branch_nilai->{$bulan},2);
                }
                else {
                    // jika belum ada
                    $data_nilai[$bulan] = [
                        $branch_nilai->branch_name => round($branch_nilai->{$bulan},2)
                    ];
                }
            }

        }

        // ----------------------------------------------------------------------------
        // ambil 3 nilai tertinggi & 3 nilai terendah
        // dan buat format data array sesuai yang dibutuhkan chart
        $data= [];
        // foreach bulan
        foreach ($rs_bulan as $key => $bulan) {
            // urutkan dari paling besar
            arsort($data_nilai[$bulan]);

            // data tertinggi
            $arr_nilai_tertinggi = array_slice($data_nilai[$bulan],0,3);

            // tertinggi 1
            if(array_key_exists('tertinggi_1', $data)) {
                // jika sudah ada
                array_push($data['tertinggi_1'], array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[0] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[0] ?? 0
                ));
            }
            else {
                $data['tertinggi_1'] = [array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[0] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[0] ?? 0
                )];
            }

            // tertinggi 2
            if(array_key_exists('tertinggi_2', $data)) {
                // jika sudah ada
                array_push($data['tertinggi_2'], array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[1] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[1] ?? 0
                ));
            }
            else {
                $data['tertinggi_2'] = [array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[1] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[1] ?? 0
                )];
            }

            // tertinggi 3
            if(array_key_exists('tertinggi_3', $data)) {
                // jika sudah ada
                array_push($data['tertinggi_3'], array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[2] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[2] ?? 0
                ));
            }
            else {
                $data['tertinggi_3'] = [array(
                    'branch'=> array_keys($arr_nilai_tertinggi)[2] ?? '',
                    'nilai' => array_values($arr_nilai_tertinggi)[2] ?? 0
                )];
            }

            // -----------------------------------------------------------------------
            // data terendah
            // data tertinggi
            $arr_nilai_terendah = array_slice($data_nilai[$bulan],-3,3);

            // tertinggi 1
            if(array_key_exists('terendah_1', $data)) {
                // jika sudah ada
                array_push($data['terendah_1'], array(
                    'branch'=> array_keys($arr_nilai_terendah)[0] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[0] ?? 0
                ));
            }
            else {
                $data['terendah_1'] = [array(
                    'branch'=> array_keys($arr_nilai_terendah)[0] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[0] ?? 0
                )];
            }

            // tertinggi 2
            if(array_key_exists('terendah_2', $data)) {
                // jika sudah ada
                array_push($data['terendah_2'], array(
                    'branch'=> array_keys($arr_nilai_terendah)[1] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[1] ?? 0
                ));
            }
            else {
                $data['terendah_2'] = [array(
                    'branch'=> array_keys($arr_nilai_terendah)[1] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[1] ?? 0
                )];
            }

            // tertinggi 3
            if(array_key_exists('terendah_3', $data)) {
                // jika sudah ada
                array_push($data['terendah_3'], array(
                    'branch'=> array_keys($arr_nilai_terendah)[2] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[2] ?? 0
                ));
            }
            else {
                $data['terendah_3'] = [array(
                    'branch'=> array_keys($arr_nilai_terendah)[2] ?? '',
                    'nilai' => array_values($arr_nilai_terendah)[2] ?? 0
                )];
            }
        }

        return $data;
    }

    // // get list branch
    // public static function getListBranch($name) {
    //     return DB::table('master_branch as a')
    //         ->select('a.id','a.name as branch_name')
    //         ->where('a.data_status','1')
    //         ->where('a.name','LIKE','%'.$name.'%')
    //         ->orderBy('a.name','asc')
    //         ->get();
    // }

    // get total komponen penilaian by branch, month, year
    public static function validatorTotalKomponenSetiapBulanBy($branch_id,$year) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$key] = DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id',$branch_id)
                ->whereMonth('b.created_date', $key)
                ->whereYear('b.created_date', $year)
                ->where('b.data_status', '1')
                ->where('b.status', 'selesai')
                ->count('a.assessment_component_id');
        }

        return $data;
    }

    // get total komponen pembersihan by branch, month, year
    public static function validatorTotalKomponenScoreSetiapBulanBy($branch_id,$year, $score) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$key] = DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id',$branch_id)
                ->whereMonth('b.created_date', $key)
                ->whereYear('b.created_date', $year)
                ->where('b.data_status', '1')
                ->where('b.status', 'selesai')
                ->where('a.score', $score)
                ->count('a.assessment_component_id');
        }

        return $data;
    }

    // rekapitulasi parameter
    public static function validatorRekapitulasiParameter() {
        $year = date('Y');
        // rs branch
        $rs_branch = DashboardModel::getListBranch('');

        // --------------------------------------------------------------
        // hitung parameter tiap rs dalam setahun
        $data = [];
        foreach ($rs_branch as $key => $value) {
            // parameter
            $total_aman = DashboardModel::validatorGetTotalKomponenParameterBy($value->id, $year,'Aman');
            $total_bersih = DashboardModel::validatorGetTotalKomponenParameterBy($value->id, $year,'Bersih');
            $total_rapih = DashboardModel::validatorGetTotalKomponenParameterBy($value->id, $year,'Rapih');
            $total_tampak_baru = DashboardModel::validatorGetTotalKomponenParameterBy($value->id, $year,'Tampak Baru');
            $total_ramah_lingkungan = DashboardModel::validatorGetTotalKomponenParameterBy($value->id, $year,'Ramah Lingkungan');
            $total_tidak_aman = DashboardModel::validatorGetTotalKomponenParameterBy($value->id, $year,'Tidak Aman');
            $total_tidak_bersih = DashboardModel::validatorGetTotalKomponenParameterBy($value->id, $year,'Tidak Bersih');
            $total_tidak_rapih = DashboardModel::validatorGetTotalKomponenParameterBy($value->id, $year,'Tidak Rapih');
            $total_tidak_tampak_baru = DashboardModel::validatorGetTotalKomponenParameterBy($value->id, $year,'Tidak Tampak Baru');
            $total_tidak_ramah_lingkungan = DashboardModel::validatorGetTotalKomponenParameterBy($value->id, $year,'Tidak Ramah Lingkungan');

            // masukan ke dalam data penampung
            $data[$key] = [
                'branch_name'=> $value->branch_name,
                'persen_aman' => ($total_aman == 0) ? 0 : ($total_aman/($total_aman+$total_tidak_aman))*100,
                'persen_bersih' => ($total_bersih == 0) ? 0 : ($total_bersih/($total_bersih+$total_tidak_bersih))*100,
                'persen_rapih' => ($total_rapih == 0) ? 0 : ($total_rapih/($total_rapih+$total_tidak_rapih))*100,
                'persen_tampak_baru' => ($total_tampak_baru == 0) ? 0 : ($total_tampak_baru/($total_tampak_baru+$total_tidak_tampak_baru))*100,
                'persen_ramah_lingkungan' => ($total_ramah_lingkungan == 0) ? 0 : ($total_ramah_lingkungan/($total_ramah_lingkungan+$total_tidak_ramah_lingkungan))*100,
            ];

        };

        // ubah ke collection
        $rs_parameter = collect($data);

        // --------------------------------------------------------------
        // hitung min,max,median,average tiap bulan seluruh rs
        // dd($rs_parameter['08']->max('persen_aman'));
        $final_data = [];
        $final_data['min'] = [
            round(floatval($rs_parameter->min('persen_aman')),2),
            round(floatval($rs_parameter->min('persen_bersih')),2),
            round(floatval($rs_parameter->min('persen_rapih')),2),
            round(floatval($rs_parameter->min('persen_tampak_baru')),2),
            round(floatval($rs_parameter->min('persen_ramah_lingkungan')),2),
        ];

        $final_data['max'] = [
            round(floatval($rs_parameter->max('persen_aman')),2),
            round(floatval($rs_parameter->max('persen_bersih')),2),
            round(floatval($rs_parameter->max('persen_rapih')),2),
            round(floatval($rs_parameter->max('persen_tampak_baru')),2),
            round(floatval($rs_parameter->max('persen_ramah_lingkungan')),2),
        ];

        $final_data['median'] = [
            round(floatval($rs_parameter->median('persen_aman')),2),
            round(floatval($rs_parameter->median('persen_bersih')),2),
            round(floatval($rs_parameter->median('persen_rapih')),2),
            round(floatval($rs_parameter->median('persen_tampak_baru')),2),
            round(floatval($rs_parameter->median('persen_ramah_lingkungan')),2),
        ];

        $final_data['average'] = [
            round(floatval($rs_parameter->average('persen_aman')),2),
            round(floatval($rs_parameter->average('persen_bersih')),2),
            round(floatval($rs_parameter->average('persen_rapih')),2),
            round(floatval($rs_parameter->average('persen_tampak_baru')),2),
            round(floatval($rs_parameter->average('persen_ramah_lingkungan')),2),
        ];

        return $final_data;
    }

    // get total komponen parameter
    public static function validatorGetTotalKomponenParameterBy($branch_id,  $year, $parameter) {
        return DB::table('branch_assessment_detail as a')
                ->join('branch_assessment as b','a.branch_assessment_id','=','b.id')
                ->where('b.branch_id', $branch_id)
                // ->whereMonth('b.created_date', $month)
                ->whereYear('b.created_date', $year)
                ->where('a.parameter', $parameter)
                ->where('b.data_status','1')
                ->where('b.status', 'selesai')
                ->count('a.parameter');
    }

    // get target rata-rata nilai
    public static function validatorGetTargetRataNilai() {
        return DB::table('app_supports')->where('key','target_rata_rata_nilai')->value('value');
    }

    // get data
    public static function validatorGetTerlambatSubmit() {
        // list ronde
        $rs_ronde = DashboardModel::validatorGetListRonde();
        $year = date('Y');

        // perhitungan
        $final_data = [
            'round_1'=> [],
            'round_2'=> [],
            'round_3'=> [],
            'round_4'=> []
        ];

        foreach ($rs_ronde as $key1 => $item) {
            # code...
            $arr_terlambat_submit = DashboardModel::validatorGetListTerlambatSubmitSemuaBulan($item->id, $year);
            foreach ($arr_terlambat_submit as $key => $value) {
                if($item->id == 1) {
                    array_push($final_data['round_1'],$value );
                }

                if($item->id == 2) {
                    array_push($final_data['round_2'],$value );
                }

                if($item->id == 3) {
                    array_push($final_data['round_3'],$value );
                }

                if($item->id == 4) {
                    array_push($final_data['round_4'],$value );
                }
            }
        }

        return $final_data;
    }

    // get submit terlambat bulanan
    public static function validatorGetListTerlambatSubmitSemuaBulan($round_id, $year ) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$value] =  DB::table('branch_assessment')
                ->where('round_id', $round_id)
                ->whereNotNull('verifikator_2_approved_by_system')
                ->whereMonth('created_date', $key)
                ->whereYear('created_date', $year)
                ->where('status','selesai')
                ->where('data_status','1')
                ->count('verifikator_2_approved_by_system');
        }
        return $data;
    }



    // list ronde
    public static function validatorGetListRonde() {
        return DB::table('master_round')
            ->select('id','name as round_name')
            ->where('data_status','1')
            ->orderBy('name','asc')
            ->get();
    }

    // ---------------------------------------------------

    // pekerjaan
    // pekerjaan selesai
    public static function validatorTotalPekerjaanTiapBulanByTahun($month, $year){
        return DB::table('branch_assignment_detail as a')
                ->select(
                    DB::raw('COUNT(a.id) as jumlah_total'),
                    DB::raw('COUNT(CASE WHEN a.status = "Belum Dikerjakan" THEN 1 ELSE NULL END) as jumlah_belum_dikerjakan'),
                    DB::raw('COUNT(CASE WHEN a.status = "Selesai" THEN 1 ELSE NULL END) as jumlah_selesai')
                )
                ->join('branch_assignment as b','a.branch_assignment_id','=','b.id')
                ->whereMonth('b.created_date', $month)
                ->whereYear('b.created_date', $year)
                ->first();
    }

    public static function validatorTotalPekerjaanTiapBulanByTahunByScore($month, $year, $score){
        return DB::table('branch_assignment_detail as a')
                ->select(
                    DB::raw('COUNT(a.id) as jumlah_total'),
                    DB::raw('COUNT(CASE WHEN a.status = "Belum Dikerjakan" THEN 1 ELSE NULL END) as jumlah_belum_dikerjakan'),
                    DB::raw('COUNT(CASE WHEN a.status = "Selesai" THEN 1 ELSE NULL END) as jumlah_selesai')
                )
                ->join('branch_assignment as b','a.branch_assignment_id','=','b.id')
                ->join('branch_assessment_detail as c','a.assessment_detail_id','=','c.id')
                ->whereMonth('b.created_date', $month)
                ->whereYear('b.created_date', $year)
                ->where('c.score', $score)
                ->first();
    }

    // get data
    public static function validatorGetPekerjaanTerlambatPersetujuan() {
        // list ronde
        $rs_ronde = DashboardModel::validatorGetListRonde();
        $year = date('Y');

        // perhitungan
        $final_data = [
            'round_1'=> [],
            'round_2'=> [],
            'round_3'=> [],
            'round_4'=> []
        ];

        foreach ($rs_ronde as $key1 => $item) {
            # code...
            $arr_terlambat_submit = DashboardModel::validatorGetListPekerjaanTerlambatPersetujuanSemuaBulan($item->id, $year);
            foreach ($arr_terlambat_submit as $key => $value) {
                if($item->id == 1) {
                    array_push($final_data['round_1'],$value );
                }

                if($item->id == 2) {
                    array_push($final_data['round_2'],$value );
                }

                if($item->id == 3) {
                    array_push($final_data['round_3'],$value );
                }

                if($item->id == 4) {
                    array_push($final_data['round_4'],$value );
                }
            }
        }

        return $final_data;
    }

    // get submit terlambat bulanan
    public static function validatorGetListPekerjaanTerlambatPersetujuanSemuaBulan($round_id, $year ) {
        $rs_bulan = parent::bulanIndo();

        $data = [];
        foreach ($rs_bulan as $key => $value) {
            $data[$value] =  DB::table('branch_assignment')
                ->where('round_id', $round_id)
                ->whereNotNull('verifikator_2_approved_by_system')
                ->whereMonth('created_date', $key)
                ->whereYear('created_date', $year)
                ->where('status','selesai')
                ->where('data_status','1')
                ->count('verifikator_2_approved_by_system');
        }
        return $data;
    }


    // -------------------------------------------------------------------------------------
    /**
     * VERIFIKATOR
     */


    // -------------------------------------------------------------------------------------
    /**
     * CHECKER
     */

    //  Progres checker
    public static function checkerGetListRonde() {
        return DB::table('master_round')
            ->select('id','name as round_name')
            ->where('data_status','1')
            ->orderBy('name','asc')
            ->get();
    }
    // get data komponen yang sudah dinilai
    public static function getDataAssessment1() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('c.data_status','1')
            ->whereNotNull('a.score')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data komponen yang sudah dinilai
    public static function getDataAssessment2() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','2')
            ->where('c.data_status','1')
            ->whereNotNull('a.score')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->orderByDesc('a.id')
            ->count();
    }
    // get data komponen yang sudah dinilai
    public static function getDataAssessment3() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->where('b.round_id','3')
            ->where('c.data_status','1')
            ->whereNotNull('a.score')
            ->orderByDesc('a.id')
            ->count();
    }
    // get data komponen yang sudah dinilai
    public static function getDataAssessment4() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('c.data_status','1')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->whereNotNull('a.score')
            ->orderByDesc('a.id')
            ->count();
    }
    // get data komponen Round 1
    public static function getKomponenRound1() {
        return DB::table('branch_assessment_detail as a')
        ->select('h.name as nama_komponen')
        ->join('branch_assessment as b','a.branch_assessment_id','b.id')
        ->join('branch_items as c','a.branch_items_id','c.id')
        ->join('master_items as d', 'c.items_id','d.id')
        ->join('master_sub_area as e', 'c.sub_area_id','e.id')
        ->join('master_area as f', 'e.area_id','f.id')
        ->join('master_location as g', 'f.location_id','g.id')
        ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
        ->where('c.branch_id', Auth::user()->branch_id)
        ->where('c.data_status','1')
        ->where('b.round_id','1')
        ->orderByDesc('a.id')
        ->whereMonth('a.created_date',date('m'))
        ->whereYear('a.created_date',date('Y'))
        ->count();
    }
    // get data komponen Round 2

    public static function getKomponenRound2() {
        return DB::table('branch_assessment_detail as a')
        ->select('h.name as nama_komponen')
        ->join('branch_assessment as b','a.branch_assessment_id','b.id')
        ->join('branch_items as c','a.branch_items_id','c.id')
        ->join('master_items as d', 'c.items_id','d.id')
        ->join('master_sub_area as e', 'c.sub_area_id','e.id')
        ->join('master_area as f', 'e.area_id','f.id')
        ->join('master_location as g', 'f.location_id','g.id')
        ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
        ->where('c.branch_id', Auth::user()->branch_id)
        ->where('b.round_id','2')
        ->where('c.data_status','1')
        ->orderByDesc('a.id')
        ->whereMonth('a.created_date',date('m'))
        ->whereYear('a.created_date',date('Y'))
        ->count();
    }
    //         // get data komponen Round 3
    public static function getKomponenRound3() {
        return DB::table('branch_assessment_detail as a')
        ->select('h.name as nama_komponen')
        ->join('branch_assessment as b','a.branch_assessment_id','b.id')
        ->join('branch_items as c','a.branch_items_id','c.id')
        ->join('master_items as d', 'c.items_id','d.id')
        ->join('master_sub_area as e', 'c.sub_area_id','e.id')
        ->join('master_area as f', 'e.area_id','f.id')
        ->join('master_location as g', 'f.location_id','g.id')
        ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
        ->where('c.branch_id', Auth::user()->branch_id)
        ->where('c.data_status','1')
        ->where('b.round_id','3')
        ->orderByDesc('a.id')
        ->whereMonth('a.created_date',date('m'))
        ->whereYear('a.created_date',date('Y'))
        ->count();
    }
    // get data komponen Round 4
    public static function getKomponenRound4() {
        return DB::table('branch_assessment_detail as a')
        ->select('h.name as nama_komponen')
        ->join('branch_assessment as b','a.branch_assessment_id','b.id')
        ->join('branch_items as c','a.branch_items_id','c.id')
        ->join('master_items as d', 'c.items_id','d.id')
        ->join('master_sub_area as e', 'c.sub_area_id','e.id')
        ->join('master_area as f', 'e.area_id','f.id')
        ->join('master_location as g', 'f.location_id','g.id')
        ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
        ->where('c.branch_id', Auth::user()->branch_id)
        ->where('b.round_id','4')
        ->where('c.data_status','1')
        ->orderByDesc('a.id')
        ->whereMonth('a.created_date',date('m'))
        ->whereYear('a.created_date',date('Y'))
        ->count();
    }

    //  Nilai Checker
    // get data komponen
    public static function getNilaiCheckerAllRound($round) {
        return DB::table('branch_assessment_detail as a')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id',$round)
            ->where('c.data_status','1')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    public static function getDataNilaiAll() {
        return DB::table('branch_assessment_detail as a')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('c.data_status','1')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }

    // get data komponen Nilai R1
    public static function getDataNilaiR1() {
        return DB::table('branch_assessment_detail as a')
            ->select('score', DB::raw('count(*) as total'),'b.round_id')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('c.data_status','1')
            ->where('b.round_id','1')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->groupBy('a.score','b.round_id')
            ->get();
    }
        // get data komponen Nilai R2
    public static function getDataNilaiR2() {
        return DB::table('branch_assessment_detail as a')
            ->select('score', DB::raw('count(*) as total'),'b.round_id')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','2')
            ->where('c.data_status','1')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->groupBy('a.score','b.round_id')
            ->get();
    }
    // get data komponen Nilai R3
    public static function getDataNilaiR3() {
        return DB::table('branch_assessment_detail as a')
            ->select('score', DB::raw('count(*) as total'),'b.round_id')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','3')
            ->where('c.data_status','1')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->groupBy('a.score','b.round_id')
            ->get();
    }
    // get data komponen Nilai R4
    public static function getDataNilaiR4() {
        return DB::table('branch_assessment_detail as a')
            ->select('score', DB::raw('count(*) as total'),'b.round_id')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('c.data_status','1')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->groupBy('a.score','b.round_id')
            ->get();
    }

    // get data komponen Nilai Pebulan
    public static function getNilaiBulan($month) {
        return DB::table('branch_assessment_detail as a')
            ->select('a.created_date','a.score')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->whereMonth('a.created_date',$month)
            ->get();
    }

    // get data komponen
    public static function getNilaiAllYear($month) {
        return DB::table('branch_assessment_detail as a')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->whereMonth('a.created_date',$month)
            ->count();
    }

    // get data parameter R1
    public static function getParameterAmanR1() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('a.parameter','Aman')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter R2
    public static function getParameterAmanR2() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','2')
            ->where('a.parameter','Aman')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter R1
    public static function getParameterAmanR3() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','3')
            ->where('a.parameter','Aman')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter R4
    public static function getParameterAmanR4() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('a.parameter','Aman')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
      // get data parameter BersihR1
      public static function getParameterBersihR1() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('a.parameter','Bersih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Bersih R2
    public static function getParameterBersihR2() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','2')
            ->where('a.parameter','Bersih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter R1
    public static function getParameterBersihR3() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','3')
            ->where('a.parameter','Bersih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter R4
    public static function getParameterBersihR4() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('a.parameter','Bersih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Rapih R1
    public static function getParameterRapihR1() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('a.parameter','Rapih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Rapih R2
    public static function getParameterRapihR2() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','2')
            ->where('a.parameter','Rapih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Rapih R3
    public static function getParameterRapihR3() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','3')
            ->where('a.parameter','Rapih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Rapih R4
    public static function getParameterRapihR4() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('a.parameter','Rapih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
        // get data parameter Tampak Baru R1
    public static function getParameterTampakBaruR1() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('a.parameter','Tampak Baru')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
        // get data parameter Tampak Baru R2
    public static function getParameterTampakBaruR2() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','2')
            ->where('a.parameter','Tampak Baru')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
        // get data parameter Tampak Baru R3
    public static function getParameterTampakBaruR3() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','3')
            ->where('a.parameter','Tampak Baru')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Tampak Baru R4
    public static function getParameterTampakBaruR4() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('a.parameter','Tampak Baru')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
        // get data parameter Ramah Lingkungan R1
    public static function getParameterRamahLingkunganR1() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('a.parameter','Ramah Lingkungan')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }

            // get data parameter Ramah Lingkungan R2
    public static function getParameterRamahLingkunganR2() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','2')
            ->where('a.parameter','Ramah Lingkungan')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Ramah Lingkungan R3
    public static function getParameterRamahLingkunganR3() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','3')
            ->where('a.parameter','Ramah Lingkungan')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Ramah Lingkungan R4
    public static function getParameterRamahLingkunganR4() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('a.parameter','Ramah Lingkungan')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }


    // get data parameter R1
    public static function getParameterTidakAmanR1() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('a.parameter','Tidak Aman')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter R2
    public static function getParameterTidakAmanR2() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','2')
            ->where('a.parameter','Tidak Aman')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter R1
    public static function getParameterTidakAmanR3() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','3')
            ->where('a.parameter','Tidak Aman')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter R4
    public static function getParameterTidakAmanR4() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('a.parameter','Tidak Aman')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
      // get data parameter BersihR1
      public static function getParameterTidakBersihR1() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('a.parameter','Tidak Bersih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Bersih R2
    public static function getParameterTidakBersihR2() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','2')
            ->where('a.parameter','Tidak Bersih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter R1
    public static function getParameterTidakBersihR3() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','3')
            ->where('a.parameter','Tidak Bersih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter R4
    public static function getParameterTidakBersihR4() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('a.parameter','Tidak Bersih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Rapih R1
    public static function getParameterTidakRapihR1() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('a.parameter','Tidak Rapih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Rapih R2
    public static function getParameterTidakRapihR2() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('a.parameter','Tidak Rapih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Rapih R3
    public static function getParameterTidakRapihR3() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','3')
            ->where('a.parameter','Tidak Rapih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Rapih R4
    public static function getParameterTidakRapihR4() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('a.parameter','Tidak Rapih')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
        // get data parameter Tampak Baru R1
    public static function getParameterTidakTampakBaruR1() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('a.parameter','Tidak Tampak Baru')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
        // get data parameter Tampak Baru R2
    public static function getParameterTidakTampakBaruR2() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','2')
            ->where('a.parameter','Tidak Tampak Baru')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
        // get data parameter Tampak Baru R3
    public static function getParameterTidakTampakBaruR3() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','3')
            ->where('a.parameter','Tidak Tampak Baru')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Tampak Baru R4
    public static function getParameterTidakTampakBaruR4() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('a.parameter','Tidak Tampak Baru')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
        // get data parameter Ramah Lingkungan R1
    public static function getParameterTidakRamahLingkunganR1() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','1')
            ->where('a.parameter','Tidak Ramah Lingkungan')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }

            // get data parameter Ramah Lingkungan R2
    public static function getParameterTidakRamahLingkunganR2() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','2')
            ->where('a.parameter','Tidak Ramah Lingkungan')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Ramah Lingkungan R3
    public static function getParameterTidakRamahLingkunganR3() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','3')
            ->where('a.parameter','Tidak Ramah Lingkungan')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }
    // get data parameter Ramah Lingkungan R4
    public static function getParameterTidakRamahLingkunganR4() {
        return DB::table('branch_assessment_detail as a')
            ->select('h.name as nama_komponen')
            ->join('branch_assessment as b','a.branch_assessment_id','b.id')
            ->join('branch_items as c','a.branch_items_id','c.id')
            ->join('master_items as d', 'c.items_id','d.id')
            ->join('master_sub_area as e', 'c.sub_area_id','e.id')
            ->join('master_area as f', 'e.area_id','f.id')
            ->join('master_location as g', 'f.location_id','g.id')
            ->join('master_assessment_component as h', 'a.assessment_component_id','h.id')
            ->where('c.branch_id', Auth::user()->branch_id)
            ->where('b.round_id','4')
            ->where('a.parameter','Tidak Ramah Lingkungan')
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date',date('m'))
            ->whereYear('a.created_date',date('Y'))
            ->count();
    }



    // get data Pekerjaan All Round 1
    public static function getPekerjaan($monthNum, $YearDate, $round_id)
    {
        return DB::table('branch_assignment_detail as a')
        ->join('branch_assignment as b', 'a.branch_assignment_id', 'b.id')
        ->where('b.branch_id', Auth::user()->branch_id)
            ->where('b.data_status', '1')
            ->where('b.round_id', $round_id)
            ->whereNot('a.description', null)
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date', $monthNum)
            ->whereYear('a.created_date', $YearDate)
            ->count();
    }


    // ===========================
    // get data Pekerjaan All Round
    public static function getPekerjaanAll($monthNum, $YearDate, $round_id)
    {
        return DB::table('branch_assignment_detail as a')
            ->join('branch_assignment as b', 'a.branch_assignment_id', 'b.id')
            ->where('b.branch_id', Auth::user()->branch_id)
            ->where('b.data_status', '1')
            ->where('b.round_id', $round_id)
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date', $monthNum)
            ->whereYear('a.created_date', $YearDate)
            ->count();
    }

    //  get data pekerjaan
    public static function getPekerjaanAllPie($monthNum, $YearDate, $nilai)
    {
        return DB::table('branch_assignment_detail as a')
            ->join('branch_assignment as b', 'a.branch_assignment_id', 'b.id')
            ->join('branch_assessment_detail as c', 'a.assessment_detail_id', 'c.id')
            ->where('b.branch_id', Auth::user()->branch_id)
            ->where('b.data_status', '1')
            ->where('c.score', $nilai)
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date', $monthNum)
            ->whereYear('a.created_date', $YearDate)
            ->count();
    }

    // get data komponen Pekerjaan
    public static function getPekerjaanPie($monthNum, $YearDate, $round_id, $nilai,$status)
    {
        return DB::table('branch_assignment_detail as a')
            ->join('branch_assignment as b', 'a.branch_assignment_id', 'b.id')
            ->join('branch_assessment_detail as c','a.assessment_detail_id','c.id')
            ->where('b.branch_id', Auth::user()->branch_id)
            ->where('b.data_status', '1')
            ->where('b.round_id', $round_id)
            ->where('c.score', $nilai)
            ->where('a.status', $status)
            ->orderByDesc('a.id')
            ->whereMonth('a.created_date', $monthNum)
            ->whereYear('a.created_date', $YearDate)
            ->count();
    }
}
