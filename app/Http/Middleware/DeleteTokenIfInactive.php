<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DeleteTokenIfInactive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $tokens = $user->tokens;

            foreach ($tokens as $token) {
                if ($token->last_used_at && now()->diffInMinutes($token->last_used_at) > 1) {
                    $token->delete();
                }
            }
        }

        return $next($request);
    }
}
