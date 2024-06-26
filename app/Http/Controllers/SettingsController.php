<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessRequest;
use App\Models\Setting;
use App\Models\Settings;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use ApiResponse;
    public function index()
    {
        return Settings::first();
    }

    public function store(Request $request) :JsonResponse
    {
        $settings = Settings::first();

        $settings->name = $request->name;

        $settings->save();

        return response()->json($settings->name);
    }

    public function access(AccessRequest $request)
    {
        $request->validated();

        $settings = Settings::first();

        $settings->has_access = true;
        $settings->next_payment = $request->next_payment;
        $settings->last_payment = Carbon::now();

        $settings->save();
    }

    public function take_access()
    {
        $settings = Settings::first();

        $settings->has_access = false;
        $settings->save();
    }

    public function switchMobileAccess(Request $request)
    {
        $data = $request->validate([
            'access' => 'required|boolean',
        ]);

        $settings = Settings::first();

        $settings->mobile_access = $data['access'];
        $settings->save();

        return $this->success();
    }
}
