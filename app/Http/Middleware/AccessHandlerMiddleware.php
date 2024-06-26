<?php

namespace App\Http\Middleware;

use App\Enums\AccessMessages;
use App\Enums\ApiResponse as ApiResponseEnum;
use App\Models\Settings;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class AccessHandlerMiddleware
{
    use ApiResponse;
    public function handle(Request $request, Closure $next)
    {
        $settings = Settings::first();

        if(!$settings->has_access) {
            return $this->notAccess(AccessMessages::NoAccess);
        }

        if ((bool)$request->header('mobile') === true) {
            if (!$settings->mobile_access){
                return response()->json([
                    'message' => ApiResponseEnum::NotAccess
                ], 403);
            }
        }


        return $next($request);
    }
}
