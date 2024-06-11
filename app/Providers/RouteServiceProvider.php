<?php

namespace App\Providers;

use App\Models\Document;
use App\Models\InventoryOperation;
use App\Models\MovementDocument;
use App\Models\OrderDocument;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Cache;
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
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(600)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            });

            Route::bind('document', function ($value) {
            $types = [
                Document::class,
                MovementDocument::class,
                OrderDocument::class,
                InventoryOperation::class
            ];

            foreach ($types as $type) {
                $document = $type::find($value);
                if ($document !== null) {
                    return $document;
                }
            }

            throw new ModelNotFoundException("Document not found");
        });



    }


}
