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
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
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
        if (Auth::check()) {
            $role = Auth::user()->role_id;

            if ($role === '01') {
                $this->app->bind(self::HOME, function () {
                    return self::HOME_ADMIN;
                });
            } elseif ($role === '02') {
                $this->app->bind(self::HOME, function () {
                    return self::HOME_TIM_CAPSTONE;
                });
            } elseif ($role === '03') {
                $this->app->bind(self::HOME, function () {
                    return self::HOME_MAHASISWA;
                });
            } elseif ($role === '04') {
                $this->app->bind(self::HOME, function () {
                    return self::HOME_DOSEN;
                });
            }
        }
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::none(); // Set the rate limit to none, effectively making it infinite
        });
    }
}
