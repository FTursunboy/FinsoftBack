<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiRequestLockMiddleware
{

    public function handle($request, Closure $next)
    {
        if ($request->isMethod('POST', 'PATCH') && $request->header('permission') === null) {

            $cacheKey = 'last_post_time_' . ($request->user()->id);

            $lastPostTime = Cache::get($cacheKey);

            if ($lastPostTime && now()->diffInSeconds($lastPostTime) < 2) {
                return response()->json(['error' => 'Too many attempts'], 429);
            }

            Cache::put($cacheKey, now(), 5);
        }


        return $next($request);
    }



}
