<?php

namespace App\Http\Controllers\Admin\Validator\Laporan;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Laporan\ParameterRSModel;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class ParameterRSController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        ParameterRSModel::authorize('R');

        $data = [
            'rs_parameter' => $this->paginate(ParameterRsModel::getDataSearch('',date('m'),date('Y'))),
            'rs_year'=> ParameterRsModel::getListYear(),
            'month'=> date('m'),
            'year'=> date('Y')
        ];

        // view
        return view('admin.validator.laporan.parameter-rs.index',$data );
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
        ParameterRsModel::authorize('R');

        // data request
        $query_all= $request->query_all;
        $month= $request->month;
        $year= $request->year;
        // new search or reset
        if($request->action == 'search') {
            // data
            $data = [
                'rs_parameter' => $this->paginate(ParameterRsModel::getDataSearch($query_all,$month,$year))->withQueryString(), 
                'query_all'=> $query_all,
                'month'=> $month,
                'year'=> $year,
                'rs_year'=> ParameterRsModel::getListYear()
            ];

            // view
            return view('admin.validator.laporan.parameter-rs.index', $data );
        }
        else {
            return redirect('/admin/validator/laporan/parameter-rumah-sakit');
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
