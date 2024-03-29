<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {

        RateLimiter::for('api.post', function (Request $request) {
            return Limit::perSecond(1)->by($request->ip());
        });

        // Apply 'api' middleware for broader API rate limiting (optional)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(22000)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Apply 'api.post' middleware only to POST routes
            Route::middleware('api.post')
                ->group(function () {
                    Route::post('/your/post/endpoint', 'YourController@postMethod');
                    // ... other POST routes
                });

            // Apply 'api' middleware (optional) for broader API rate limiting
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting()
    {
        // ALLOW 1500/60 = 25 Request/Sec
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(1500);
        });


        // ALLOW 1000/60 = 16.66 Request/Sec
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(1000)->response(function () {
                return response('Too many request...', 429);
            });
        });
    }
}
