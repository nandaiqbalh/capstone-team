<?php

namespace App\Http\Controllers\Admin\Validator\Laporan;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Laporan\TerlambatSubmitModel;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class TerlambatSubmitController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        TerlambatSubmitModel::authorize('R');

        $data = [
            'rs_terlambat_submit'   => TerlambatSubmitModel::getDataSearch('', date('Y')),
            'rs_bulan'              => TerlambatSubmitModel::bulanIndo(),
            'rs_year'               => TerlambatSubmitModel::getListYear(),
            'year'                  => date('Y')
        ];

        // dd($data);
        // view
        return view('admin.validator.laporan.terlambat-submit.index', $data);
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
        TerlambatSubmitModel::authorize('R');

        // data request
        $query_all  = $request->query_all;
        $year       = $request->year;
        // new search or reset
        if($request->action == 'search') {
            // data
            $data = [
                'rs_terlambat_submit'   => TerlambatSubmitModel::getDataSearch($query_all,$year), 
                'query_all'             => $query_all,
                'year'                  => $year,
                'rs_bulan'              => TerlambatSubmitModel::bulanIndo(),
                'rs_year'               => TerlambatSubmitModel::getListYear()
            ];

            // view
            return view('admin.validator.laporan.terlambat-submit.index', $data );
        }
        else {
            return redirect('/admin/validator/laporan/terlambat-submit');
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
       TerlambatSubmitModel::authorize('U');

        // get data
        $rs_terlambat_submit = TerlambatSubmitModel::getDetail($id,$month, $year);
        
        // if exist
        if(!empty($rs_terlambat_submit)) {
            $arr_bulan = TerlambatSubmitModel::bulanIndo();

            $data = [
                'rs_terlambat_submit'   => $rs_terlambat_submit,
                'round'                 => TerlambatSubmitModel::getRoundById($id),
                'bulan'                 => $arr_bulan[$month]
            ];
            //view
            return view('admin.validator.laporan.terlambat-submit.detail', $data);
        }
        else {
            // flash message
            session()->flash('danger', 'Data tidak ditemukan.');
            return redirect('/admin/validator/laporan/terlambat-submit');
        }
    }

    /**
     * The attributes that are mass assignable. custom pagination
     *
     * @var array
     */
    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page   = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items  = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path'=> URL::current()]);
    }

}
