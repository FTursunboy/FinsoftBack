<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AtomicLockMiddleware
{
    public function handle($request, Closure $next)
    {
        $lockKey = $request->header('lock_secret');
        $lockDuration = 5;

        $uniqueLockKey = $lockKey . ':' . now()->timestamp;

        if (!Cache::has($uniqueLockKey)) {

            if (Cache::add($uniqueLockKey, true, $lockDuration)) {
                $response = $next($request);

                Cache::forget($uniqueLockKey);

                return $response;
            }
        }
        // Если блокировка уже существует, возвращаем ошибку
        return response()->json(['message' => 'Request is already being processed.'], 429);
    }
}
