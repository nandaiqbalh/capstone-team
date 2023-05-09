<?php

namespace App\Http\Controllers\Admin\Validator\Laporan;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Laporan\TerlambatPersetujuanModel;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class TerlambatPersetujuanController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        TerlambatPersetujuanModel::authorize('R');

        $data = [
            'rs_terlambat_submit'   => TerlambatPersetujuanModel::getDataSearch('', date('Y')),
            'rs_bulan'              => TerlambatPersetujuanModel::bulanIndo(),
            'rs_year'               => TerlambatPersetujuanModel::getListYear(),
            'year'                  => date('Y')
        ];

        // view
        return view('admin.validator.laporan.terlambat-persetujuan.index', $data);
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
        TerlambatPersetujuanModel::authorize('R');

        // data request
        $query_all = $request->query_all;
        $year= $request->year;
        // new search or reset
        if($request->action == 'search') {
            // data
            $data = [
                'rs_terlambat_submit'   => TerlambatPersetujuanModel::getDataSearch($query_all,$year), 
                'query_all'             => $query_all,
                'year'                  => $year,
                'rs_bulan'              => TerlambatPersetujuanModel::bulanIndo(),
                'rs_year'               => TerlambatPersetujuanModel::getListYear()
            ];

            // view
            return view('admin.validator.laporan.terlambat-persetujuan.index', $data );
        }
        else {
            return redirect('/admin/validator/laporan/terlambat-persetujuan');
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
       TerlambatPersetujuanModel::authorize('U');

        // get data
        $rs_terlambat_submit = TerlambatPersetujuanModel::getDetail($id,$month, $year);
        
        // if exist
        if(!empty($rs_terlambat_submit)) {
            $arr_bulan = TerlambatPersetujuanModel::bulanIndo();

            $data = [
                'rs_terlambat_submit'=> $rs_terlambat_submit,
                'round'=> TerlambatPersetujuanModel::getRoundById($id),
                'bulan'=> $arr_bulan[$month]
            ];
            //view
            return view('admin.validator.laporan.terlambat-persetujuan.detail', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/laporan/terlambat-persetujuan');
        }
    }

    /**
     * The attributes that are mass assignable. custom pagination
     *
     * @var array
     */
    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path'=> URL::current()]);
    }

}
