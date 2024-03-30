<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiRequestLockMiddleware
{

    public function handle($request, Closure $next)
    {

        if ($request->isMethod('POST', 'PATCH')) {
            $cacheKey = 'last_post_time_' . ($request->user()?->id);
            $lastPostTime = Cache::get($cacheKey);

            Log::info($lastPostTime);
            if ($lastPostTime && now()->diffInSeconds($lastPostTime) < 5) {
                return response()->json(['error' => 'Too many attempts'], 429);
            }

            Log::error($request->ip());
            Cache::put($cacheKey, now(), 5);
        }

        return $next($request);
    }



}
