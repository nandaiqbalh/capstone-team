<?php

namespace App\Http\Controllers\Admin\Validator\Laporan;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Laporan\PekerjaanTerlambatPersetujuanModel;

class PekerjaanTerlambatPersetujuanController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        PekerjaanTerlambatPersetujuanModel::authorize('R');

        $data = [
            'rs_terlambat_persetujuan'   => PekerjaanTerlambatPersetujuanModel::getDataSearch('', date('Y')),
            'rs_bulan'              => PekerjaanTerlambatPersetujuanModel::bulanIndo(),
            'rs_year'               => PekerjaanTerlambatPersetujuanModel::getListYear(),
            'year'                  => date('Y')
        ];

        // view
        return view('admin.validator.laporan.pekerjaan-terlambat-persetujuan.index', $data);
    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // authorize
        PekerjaanTerlambatPersetujuanModel::authorize('R');

        // data request
        $query_all = $request->query_all;
        $year= $request->year;
        // new search or reset
        if($request->action == 'search') {
            // data
            $data = [
                'rs_terlambat_persetujuan'  => PekerjaanTerlambatPersetujuanModel::getDataSearch($query_all,$year), 
                'query_all'                 => $query_all,
                'year'                      => $year,
                'rs_bulan'                  => PekerjaanTerlambatPersetujuanModel::bulanIndo(),
                'rs_year'                   => PekerjaanTerlambatPersetujuanModel::getListYear()
            ];

            // view
            return view('admin.validator.laporan.pekerjaan-terlambat-persetujuan.index', $data );
        }
        else {
            return redirect('/admin/validator/laporan/pekerjaan-terlambat-persetujuan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id,$month, $year)
    {
        // authorize
       PekerjaanTerlambatPersetujuanModel::authorize('U');

        // get data
        $rs_terlambat_persetujuan = PekerjaanTerlambatPersetujuanModel::getDetail($id,$month, $year);
        
        // if exist
        if(!empty($rs_terlambat_persetujuan)) {
            $arr_bulan = PekerjaanTerlambatPersetujuanModel::bulanIndo();

            $data = [
                'rs_terlambat_persetujuan'  => $rs_terlambat_persetujuan,
                'round'                     => PekerjaanTerlambatPersetujuanModel::getRoundById($id),
                'bulan'                     => $arr_bulan[$month]
            ];
            //view
            return view('admin.validator.laporan.pekerjaan-terlambat-persetujuan.detail', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/laporan/pekerjaan-terlambat-persetujuan');
        }
    }


}
