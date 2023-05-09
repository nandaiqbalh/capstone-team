<?php

namespace App\Http\Controllers\Admin\Validator\Laporan;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Laporan\RataNilaiModel;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class RataNilaiController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        RataNilaiModel::authorize('R');
        
        $data = [
            'rs_rata_nilai'=> $this->paginate(RataNilaiModel::getData()),
            'rs_total_rata_nilai'=> RataNilaiModel::validatorRekapitulasiNilai(),
            'rs_bulan'=> RataNilaiModel::bulanIndo(),
            'rs_year'=> RataNilaiModel::getListYear(),
            'year'=> date('Y')
        ];

        // view
        return view('admin.validator.laporan.rata-nilai.index', $data);
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
        RataNilaiModel::authorize('R');

        // data request
        $query_all = $request->query_all;
        $year= $request->year;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_rata_nilai = $this->paginate(RataNilaiModel::getDataSearch($query_all,$year))->withQueryString();
            // data
            $data = [
                'rs_rata_nilai'         => $rs_rata_nilai,
                'rs_total_rata_nilai'   => RataNilaiModel::validatorRekapitulasiNilaiSearch($query_all, $year), 
                'query_all'             => $query_all,
                'year'                  => $year,
                'rs_bulan'              => RataNilaiModel::bulanIndo(),
                'rs_year'               => RataNilaiModel::getListYear()
            ];

            // dd($data);

            // view
            return view('admin.validator.laporan.rata-nilai.index', $data );
        }
        else {
            return redirect('/admin/validator/laporan/rata-rata-nilai');
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
