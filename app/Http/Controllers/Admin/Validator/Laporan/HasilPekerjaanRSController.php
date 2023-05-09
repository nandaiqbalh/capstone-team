<?php

namespace App\Http\Controllers\Admin\Validator\Laporan;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Validator\Laporan\HasilPekerjaanRSModel;

// use Illuminate\Pagination\Paginator;
// use Illuminate\Pagination\LengthAwarePaginator;
// use Illuminate\Support\Collection;
// use Illuminate\Support\Facades\URL;

class HasilPekerjaanRSController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        HasilPekerjaanRSModel::authorize('R');

        $data = [
            'rs_perbaikan'      => HasilPekerjaanRSModel::getDataPerbaikan('',date('Y')),
            'rs_bulan'          => HasilPekerjaanRSModel::bulanIndo(),
            'rs_year'           => HasilPekerjaanRSModel::getListYear(),
            'year'              => date('Y')
        ];
        
        // view
        return view('admin.validator.laporan.hasil-pekerjaan-rs.index',$data );
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
        HasilPekerjaanRSModel::authorize('R');

        // data request
        $query_all  = $request->query_all;
        $year       = $request->year;
        // new search or reset
        if($request->action == 'search') {
            // data
            $data = [
                'rs_perbaikan'      => HasilPekerjaanRSModel::getDataPerbaikan($query_all,$year),
                'rs_bulan'          => HasilPekerjaanRSModel::bulanIndo(),
                'rs_year'           => HasilPekerjaanRSModel::getListYear(),
                'year'              => $year ,
                'query_all'         => $query_all
            ];


            // view
            return view('admin.validator.laporan.hasil-pekerjaan-rs.index', $data );
        }
        else {
            return redirect('/admin/validator/laporan/hasil-pekerjaan-rumah-sakit');
        }
    }

     /**
     * The attributes that are mass assignable. custom pagination
     *
     * @var array
     */
    // public function paginate($items, $perPage = 10, $page = null, $options = [])
    // {
    //     $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    //     $items = $items instanceof Collection ? $items : Collection::make($items);
    //     return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path'=> URL::current()]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPenggantian()
    {
        // authorize
        HasilPekerjaanRSModel::authorize('R');

        $data = [
            'rs_penggantian'    => HasilPekerjaanRSModel::getDataPenggantian('',date('Y')),
            'rs_bulan'          => HasilPekerjaanRSModel::bulanIndo(),
            'rs_year'           => HasilPekerjaanRSModel::getListYear(),
            'year'              => date('Y')
        ];
        
        // view
        return view('admin.validator.laporan.hasil-pekerjaan-rs.penggantian',$data );
    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchPenggantian(Request $request)
    {
        // authorize
        HasilPekerjaanRSModel::authorize('R');

        // data request
        $query_all  = $request->query_all;
        $year       = $request->year;
        // new search or reset
        if($request->action == 'search') {
            // data
            $data = [
                'rs_penggantian'    => HasilPekerjaanRSModel::getDataPenggantian($query_all,$year),
                'rs_bulan'          => HasilPekerjaanRSModel::bulanIndo(),
                'rs_year'           => HasilPekerjaanRSModel::getListYear(),
                'year'              => $year ,
                'query_all'         => $query_all
            ];


            // view
            return view('admin.validator.laporan.hasil-pekerjaan-rs.penggantian', $data );
        }
        else {
            return redirect('/admin/validator/laporan/hasil-pekerjaan-rumah-sakit/penggantian');
        }
    }

}
