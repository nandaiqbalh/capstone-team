<?php

namespace App\Http\Controllers\Admin\Validator\Laporan;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Laporan\RekapitulasiNilaiModel;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class RekapitulasiNilaiController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        RekapitulasiNilaiModel::authorize('R');

        $data = [
            'rs_rekapitulasi_nilai'=> $this->paginate(RekapitulasiNilaiModel::getData()),
            'rs_bulan'=> RekapitulasiNilaiModel::bulanIndo(),
            'rs_year'=> RekapitulasiNilaiModel::getListYear(),
            'year'=> date('Y')
        ];

        // view
        return view('admin.validator.laporan.rekapitulasi-nilai-rs.index', $data);
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
        RekapitulasiNilaiModel::authorize('R');

        // data request
        $query_all = $request->query_all;
        $year= $request->year;
        // new search or reset
        if($request->action == 'search') {
            // get data with pagination
            $rs_rekapitulasi_nilai = $this->paginate(RekapitulasiNilaiModel::getDataSearch($query_all,$year))->withQueryString();
            // data
            $data = [
                'rs_rekapitulasi_nilai' => $rs_rekapitulasi_nilai, 
                'query_all'=> $query_all,
                'year'=> $year,
                'rs_bulan'=> RekapitulasiNilaiModel::bulanIndo(),
                'rs_year'=> RekapitulasiNilaiModel::getListYear()
            ];

            // dd($data);

            // view
            return view('admin.validator.laporan.rekapitulasi-nilai-rs.index', $data );
        }
        else {
            return redirect('/admin/validator/laporan/rekapitulasi-nilai');
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
