<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

     /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // pagination
        Paginator::useBootstrap();
        // Paginator::useBootstrapFive();

        // DB::listen(function ($query) {
        //     $sql = $query->sql;
        //     // abaikan query get
        //     if(Str::contains($sql,'select')){
        //         // nothing
        //     }
        //     else {
        //         $action = Auth::user()->user_name.' '.strtok($sql,' ').' data';
        //         $data = [
        //             'sql'       => $sql,
        //             'bindings'  => $query->bindings,
        //             'time'      => $query->time
        //         ];
    
        //         Log::info($action,$data);

        //     }
        // });
    }
}
