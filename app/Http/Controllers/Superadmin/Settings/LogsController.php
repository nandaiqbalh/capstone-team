<?php

namespace App\Http\Controllers\Superadmin\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TimCapstone\BaseController;
use App\Models\Superadmin\Settings\LogsModel;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class LogsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        LogsModel::authorize('R');
        // default log today
        // pattern
        $pattern = "/^\[(?<date>.*)\]\s(?<env>\w+)\.(?<type>\w+):(?<message>.*)/m";
        // get log file as string
        $file_name = storage_path().'/logs/laravel-'.date('Y-m-d').'.log';
        if(!is_file($file_name)){
            // jika tidak ada file log
            $logs = [];
        }
        else {

            $content = file_get_contents($file_name);
            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER, 0);

            $logs = [];
            foreach ($matches as $match) {
                $logs[] = (object) [
                    'timestamp' => $match['date'],
                    'env' => $match['env'],
                    'type' => $match['type'],
                    'message' => trim($match['message'])
                ];
            }
        }


        // balik array dan buat pagination
        $rs_logs = $this->paginate(array_reverse($logs));

        $data = [
            'rs_logs'=> $rs_logs
        ];

        return view('tim_capstone.settings.logs.index', $data);

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
        LogsModel::authorize('R');

        // data request
        $date = $request->date;

        // new search or reset
        if($request->action == 'search') {
           // pattern
            $pattern = "/^\[(?<date>.*)\]\s(?<env>\w+)\.(?<type>\w+):(?<message>.*)/m";
            // get log file as string
            $file_name = storage_path().'/logs/laravel-'.$date.'.log';
            if(!is_file($file_name)){
                // jika tidak ada file log
                $logs = [];
            }
            else {

                $content = file_get_contents($file_name);
                preg_match_all($pattern, $content, $matches, PREG_SET_ORDER, 0);

                $logs = [];
                foreach ($matches as $match) {
                    $logs[] = (object) [
                        'timestamp' => $match['date'],
                        'env' => $match['env'],
                        'type' => $match['type'],
                        'message' => trim($match['message'])
                    ];
                }
            }


            // balik array dan buat pagination
            $rs_logs = $this->paginate(array_reverse($logs));

            $data = [
                'rs_logs'=> $rs_logs,
                'date'  => $date
            ];

            return view('tim_capstone.settings.logs.index', $data);
        }
        else {
            return redirect('/admin/settings/logs');
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

    // ------------------------------------------------------------------------------------
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexLogin()
    {
        // authorize
        LogsModel::authorize('R');

        $data = [
            'rs_logs'=> LogsModel::getLogLogin()
        ];

        return view('tim_capstone.settings.logs.login.index', $data);

    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchLogin(Request $request)
    {
        // authorize
        LogsModel::authorize('R');

        // data request
        $date = $request->date;

        // new search or reset
        if($request->action == 'search') {

            $data = [
                'rs_logs'=> LogsModel::getLogLoginBy($date),
                'date'  => $date
            ];

            return view('tim_capstone.settings.logs.login.index', $data);
        }
        else {
            return redirect('/admin/settings/logs/login');
        }
    }

    // ------------------------------------------------------------------------------------
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexLoginAttempt()
    {
        // authorize
        LogsModel::authorize('R');

        $data = [
            'rs_logs'=> LogsModel::getLogLoginAttempt()
        ];

        return view('tim_capstone.settings.logs.login-attempt.index', $data);

    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchLoginAttempt(Request $request)
    {
        // authorize
        LogsModel::authorize('R');

        // data request
        $date = $request->date;

        // new search or reset
        if($request->action == 'search') {

            $data = [
                'rs_logs'=> LogsModel::getLogLoginAttemptBy($date),
                'date'  => $date
            ];

            return view('tim_capstone.settings.logs.login-attempt.index', $data);
        }
        else {
            return redirect('/admin/settings/logs/login-attempt');
        }
    }

    // ------------------------------------------------------------------------------------
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexResetPassword()
    {
        // authorize
        LogsModel::authorize('R');

        $data = [
            'rs_logs'=> LogsModel::getResetPassword()
        ];

        return view('tim_capstone.settings.logs.reset-password.index', $data);

    }

    /**
     * Search data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchResetPassword(Request $request)
    {
        // authorize
        LogsModel::authorize('R');

        // data request
        $date = $request->date;

        // new search or reset
        if($request->action == 'search') {

            $data = [
                'rs_logs'=> LogsModel::getResetPasswordBy($date),
                'date'  => $date
            ];

            return view('tim_capstone.settings.logs.reset-password.index', $data);
        }
        else {
            return redirect('/admin/settings/logs/reset-password');
        }
    }
}
