<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{

    const HOME_ADMIN = '/admin/dashboard';
    const HOME_TIM_CAPSTONE = '/tim-capstone/beranda';
    const HOME_MAHASISWA = '/mahasiswa/beranda';
    const HOME_DOSEN = '/dosen/beranda';

    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        // Redirect based on user role
        $this->map();
    }

    protected function map()
    {
        Route::middleware('web')
            ->group(function () {
                Route::middleware('auth')
                    ->group(function () {
                        // Redirect based on user role
                        if (Auth::check()) {
                            $role = Auth::user()->role_id;
                            switch ($role) {
                                case '01':
                                    $this->redirectUser(self::HOME_ADMIN);
                                    break;
                                case '02':
                                    $this->redirectUser(self::HOME_TIM_CAPSTONE);
                                    break;
                                case '03':
                                    $this->redirectUser(self::HOME_MAHASISWA);
                                    break;
                                case '04':
                                    $this->redirectUser(self::HOME_DOSEN);
                                    break;
                                default:
                                    $this->redirectUser(self::HOME_MAHASISWA);
                                    break;
                            }
                        }
                    });
            });
    }

    protected function redirectUser($homeRoute)
    {
        Route::get('/', function () use ($homeRoute) {
            return redirect($homeRoute);
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::none(); // Set the rate limit to none, effectively making it infinite
        });
    }
}
