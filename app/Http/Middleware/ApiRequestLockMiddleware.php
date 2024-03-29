<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class ApiRequestLockMiddleware
{
    public function handle($request, Closure $next)
    {
        $lockKey = 'api_request_lock';

        if (Cache::has($lockKey)) {
              return response()->json(['error' => 'Request already in progress'], 409);
        }

        Cache::put($lockKey, true, now()->addSeconds(30));


        $response = $next($request);

        Cache::forget($lockKey);

        return $response;
    }
}
