<?php

namespace App\Helpers;

class DateIndonesia {

    // init var
    protected static $arr_lang = array();
    protected static $arr_hari = array();
    protected static $bulan_romawi = array();

    // constructor
    function __construct() {
        // indonesia
        static::$arr_lang['in'] = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
        // english
        static::$arr_lang['en'] = array('01' => 'January', '02' => 'February', '03' => 'Maret', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
        // indonesia short date
        static::$arr_lang['ins'] = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des');
        // english short date
        static::$arr_lang['ens'] = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ags', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
        // hari indonesia
        static::$arr_hari = array('1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu');
        // bulan romawi
        static::$bulan_romawi = array('01' => 'I', '02' => 'II', '03' => 'III', '04' => 'IV', '05' => 'V', '06' => 'VI', '07' => 'VII', '08' => 'VIII', '09' => 'IX', '10' => 'X', '11' => 'XI', '12' => 'XII');
    }

    // get array month
    public static function get_month($lang = "in") {
        return static::$arr_lang[$lang];
    }

    // get array month
    public static function get_bulan($month) {
        return static::$arr_lang['in'][$month];
    }

    // get bulan romawi
    public static function get_bulan_romawi($bulan = "") {
        return static::$bulan_romawi[$bulan];
    }

    public static function get_full_date($tgl, $lang = "in") {
        if ($tgl == '0000-00-00') {
            return '';
        }
        // check input tanggal
        $tgl = explode(' ', $tgl);
        $tgl_day = isset($tgl[0]) ? $tgl[0] : '00-00-0000';
        $tgl_jam = isset($tgl[1]) ? $tgl[1] : '';
        // tanggal
        $tgl_day = explode('-', $tgl_day);
        if (count($tgl_day) != 3) {
            return '';
        }
        // jam
        $tgl_jam = explode(':', $tgl_jam);
        if (count($tgl_jam) != 3) {
            $tgl_jam = '';
        }
        // parse date
        $month_label = isset(static::$arr_lang[$lang][$tgl_day[1]]) ? static::$arr_lang[$lang][$tgl_day[1]] : static::$arr_lang[$lang]['00'];
        $date_label = $tgl_day[2] . ' ' . $month_label . ' ' . $tgl_day[0];
        // parse time
        if (!empty($tgl_jam)) {
            $tgl_jam = $tgl_jam[0] . ':' . $tgl_jam[1] . ':' . $tgl_jam[2];
            $date_label .= ' Jam ' . $tgl_jam;
        }
        // return
        return $date_label;
    }

    public static function get_tanggal_bulan($tgl, $lang = "in") {
        // check input tanggal
        $tgl = explode(' ', $tgl);
        $tgl_day = isset($tgl[0]) ? $tgl[0] : '00-00-0000';
        // tanggal
        $tgl_day = explode('-', $tgl_day);
        if (count($tgl_day) != 3) {
            return '';
        }
        // parse date
        $month_label = isset(static::$arr_lang[$lang][$tgl_day[1]]) ? static::$arr_lang[$lang][$tgl_day[1]] : static::$arr_lang[$lang]['00'];
        $date_label = $tgl_day[2] . ' ' . $month_label;
        // return
        return $date_label;
    }

    public static function get_short_date($tgl) {
        if ($tgl == '0000-00-00') {
            return '';
        }
        // check input tanggal
        $tgl = explode(' ', $tgl);
        $tgl_day = isset($tgl[0]) ? $tgl[0] : '00-00-0000';
        $tgl_jam = isset($tgl[1]) ? $tgl[1] : '';
        // tanggal
        $tgl_day = explode('-', $tgl_day);
        if (count($tgl_day) != 3) {
            return '';
        }
        // jam
        $tgl_jam = explode(':', $tgl_jam);
        if (count($tgl_jam) != 3) {
            $tgl_jam = '';
        }
        // parse date
        $date_label = $tgl_day[0] . '/' . $tgl_day[1] . '/' . $tgl_day[2];
        // parse time
        if (!empty($tgl_jam)) {
            $tgl_jam = $tgl_jam[0] . ':' . $tgl_jam[1] . ':' . $tgl_jam[2];
            $date_label .= ' ' . $tgl_jam;
        }
        // return
        return $date_label;
    }

    public static function get_bulan_indonesia($lang = "in") {
        $month = static::$arr_lang[$lang];
        unset($month['00']);
        return $month;
    }

    // get date now
    public static function get_date_now() {
        // hari ind
        $date['hari'] = static::$arr_hari[date('N')];
        // tanggal
        $date['tanggal'] = date('d');
        // bulan ind
        $date['bulan'] = static::$arr_lang['in'][date('m')];
        // tahun
        $date['tahun'] = date('Y');
        // return
        return $date;
    }

    // get date now
    public static function get_date_indonesia($date_indo) {
        // hari ind
        $hari = date("N", strtotime($date_indo));
        $date['hari'] = static::$arr_hari[$hari];
        // tanggal
        $tanggal = date("d", strtotime($date_indo));
        $date['tanggal'] = $tanggal;
        // bulan ind
        $bulan = date("m", strtotime($date_indo));
        $date['bulan'] = static::$arr_lang['in'][$bulan];
        $date['numeric_bulan'] = date("m", strtotime($date_indo));
        // tahun
        $tahun = date("Y", strtotime($date_indo));
        $date['tahun'] = $tahun;
        // jam
        $jam = date("H", strtotime($date_indo));
        $date['jam'] = $jam;
        // menit
        $menit = date("i", strtotime($date_indo));
        $date['menit'] = $menit;
        // detik
        $detik = date("s", strtotime($date_indo));
        $date['detik'] = $detik;
        // return
        return $date;
    }

    function nicetime($date) {
        if (empty($date)) {
            return "";
        }

        $periods = array("detik", "menit", "jam", "hari", "minggu", "bulan", "tahun", "dekade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        $now = time();
        $unix_date = strtotime($date);

        // check validity of date
        if (empty($unix_date)) {
            return "";
        }

        // is it future date or past date
        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "yang lalu";
        } else {
            $difference = $unix_date - $now;
            $tense = "yang lalu";
        }

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j] .= "";
        }

        return "$difference $periods[$j] {$tense}";
    }

    /* ==================== EDIT ==================== */

    // get date only
    public static function get_date_only($tgl, $lang = "in") {
        if ($tgl == '0000-00-00') {
            return '';
        }
        // check input tanggal
        $tgl = explode(' ', $tgl);
        $tgl_day = isset($tgl[0]) ? $tgl[0] : '00-00-0000';
        // tanggal
        $tgl_day = explode('-', $tgl_day);
        if (count($tgl_day) != 3) {
            return '';
        }
        // parse date
        $month_label = isset(static::$arr_lang[$lang][$tgl_day[1]]) ? static::$arr_lang[$lang][$tgl_day[1]] : static::$arr_lang[$lang]['00'];
        $date_label = $tgl_day[2] . ' ' . $month_label . ' ' . $tgl_day[0];
        // return
        return $date_label;
    }

    // get date only
    public static function get_date_short_only($tgl, $lang = "in") {
        if ($tgl == '0000-00-00') {
            return '';
        }
        // check input tanggal
        $tgl = explode(' ', $tgl);
        $tgl_day = isset($tgl[0]) ? $tgl[0] : '00-00-0000';
        // tanggal
        $tgl_day = explode('-', $tgl_day);
        if (count($tgl_day) != 3) {
            return '';
        }
        $tgl_jam = isset($tgl[1]) ? $tgl[1] : '';
        // jam
        $tgl_jam = explode(':', $tgl_jam);
        if (count($tgl_jam) != 3) {
            $tgl_jam = '';
        }
        // parse date
        $month_label = isset(static::$arr_lang[$lang][$tgl_day[1]]) ? static::$arr_lang[$lang][$tgl_day[1]] : static::$arr_lang[$lang]['00'];
        $date_label = $tgl_day[2] . ' ' . substr($month_label, 0, 3) . ' ' . $tgl_day[0];
        // parse time
        if (!empty($tgl_jam)) {
            $tgl_jam = $tgl_jam[0] . ':' . $tgl_jam[1] . ':' . $tgl_jam[2];
            $date_label .= ' ' . $tgl_jam;
        }
        // return
        return $date_label;
    }

    // get date only
    public static function get_time_only($date_indo) {
        // cekl params
        if ($date_indo != '') {
            // tahun
            $time = date("H:i", strtotime($date_indo));
            // return
            return $time;
        } else {
            return '';
        }
    }

    // frekuensi dos
    public static function get_frequensi_dos($dos = '0000000') {
        // check input tanggal
        $frekuensi = 0;
        for ($i = 0; $i <= 6; $i++) {
            $sign = substr($dos, $i, 1);
            if ($sign <> '0') {
                $frekuensi++;
            }
        }
        // return
        return $frekuensi;
    }

    public static function get_short_label_date($tgl) {
        // check input tanggal
        if ($tgl == '') {
            return '';
        }
        // parse date
        $date_label = date("d", strtotime($tgl)) . '-' . date("m", strtotime($tgl)) . '-' . date("Y", strtotime($tgl));
        // return
        return $date_label;
    }

    // convert UTC to local time
    public static function get_local_time_from_utc($utc = '', $local_sign = '', $local_time = '') {
        // $utc = "06:58:00";
        // $local_sign = + untuk penambahan;
        // $local_time = "07:00:00" untuk jakarta;
        $utc = trim($utc);
        if (!empty($utc)) {
            $utc = substr($utc, 0, 2) . ':' . substr($utc, 2, 2) . ':00';
            $local_time = $local_time . ':00';
            $result = '00:00';
            $secs = strtotime($local_time) - strtotime("00:00:00");
            if ($local_sign == '+') {
                // jika +
                $result = date("H:i:s", strtotime($utc) + $secs);
            } elseif ($local_sign == '-') {
                // jika -
                $result = date("H:i", strtotime($utc) - $secs);
            }
            return substr($result, 0, 5);
        }
    }

    public static function get_month_year($tgl, $lang = 'in') {
        // tanggal
        $tgl_day = explode('-', $tgl);
        // bulan
        $month_label = isset(static::$arr_lang[$lang][$tgl_day[1]]) ? static::$arr_lang[$lang][$tgl_day[1]] : static::$arr_lang[$lang]['00'];
        $month_label = isset(static::$arr_lang[$lang][$tgl_day[1]]) ? static::$arr_lang[$lang][$tgl_day[1]] : static::$arr_lang[$lang]['00'];
        // return
        return $month_label . ' ' . $tgl_day[0];
    }

    public static function get_month_only($tgl, $lang = 'in') {
        // tanggal
        $tgl_day = explode('-', $tgl);
        // bulan
        $month_label = isset(static::$arr_lang[$lang][$tgl_day[1]]) ? static::$arr_lang[$lang][$tgl_day[1]] : static::$arr_lang[$lang]['00'];
        $month_label = isset(static::$arr_lang[$lang][$tgl_day[1]]) ? static::$arr_lang[$lang][$tgl_day[1]] : static::$arr_lang[$lang]['00'];
        // return
        return $month_label;
    }
    
    public static function change_format_to_dmy($tgl){
        if(empty($tgl) || $tgl == '0000-00-00'){
            return '';
        }
        return date("d-m-Y", strtotime($tgl));
    }
    
    public static function change_format_to_ymd($tgl){
        if(empty($tgl) || $tgl == '00-00-0000'){
            return '';
        }
        return date("Y-m-d", strtotime($tgl));
    }

    // calculate age from birth day
    public static function calculate_age($tgl) {
        $birth_day = new \DateTime($tgl);
        $today  = new \DateTime('today');
        return $birth_day->diff($today)->y;
    }

    // get time indonesia from time format
    public static function get_time_indo($time) {
        // from H:i:s to H.i
        return str_replace(':','.', substr( $time,'0','5'));
    }

    /**
     * CUSTOM
     */
    // get day only
    public static function get_day_only2($tgl) {
        $arr_day = array('1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', '7' => 'Minggu');;
        
        $timestamp = strtotime($tgl);
        $day = date('w', $timestamp);

        if($day == '0') {
            $day = '7';
            return $arr_day[$day];
        }
        else {
            return $arr_day[$day];
        }
    }

    // get date only
    public static function get_date_only2($tgl) {
        $arr_lang = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
        if ($tgl == '0000-00-00') {
            return '';
        }
        // check input tanggal
        $tgl = explode(' ', $tgl);
        $tgl_day = isset($tgl[0]) ? $tgl[0] : '00-00-0000';
        // tanggal
        $tgl_day = explode('-', $tgl_day);
        if (count($tgl_day) != 3) {
            return '';
        }
        // parse date
        $month_label = isset($arr_lang[$tgl_day[1]]) ? $arr_lang[$tgl_day[1]] : $arr_lang['00'];
        $date_label = $tgl_day[2] . ' ' . $month_label . ' ' . $tgl_day[0];
        // return
        return $date_label;
    }

    public static function get_month_year2($tgl) {
        $arr_lang = array('01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember');
        // tanggal
        $tgl_day = explode('-', $tgl);
        // bulan
        $month_label = isset($arr_lang[$tgl_day[1]]) ? $arr_lang[$tgl_day[1]] : $arr_lang['00'];
        // return
        return $month_label . ' ' . $tgl_day[0];
    }

    // get range date beetween to date
    public static function get_range_date($start_date, $end_date) {
        $first_day = date("d", strtotime($start_date));
        $second_day = date("d", strtotime($end_date));
        // jika first day & second day sama
        if($first_day == $second_day){
            $tgl = $first_day;
        }
        else {
            $tgl = $first_day.'-'.$second_day;
        }
        
        $month_year = DateIndonesia::get_month_year($start_date);

        return $tgl.' '.$month_year;
    }

}