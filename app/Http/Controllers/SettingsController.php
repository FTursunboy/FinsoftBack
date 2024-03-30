<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return Setting::first();
    }

    public function store(Request $request) :JsonResponse
    {

        $setings = Setting::firstOrNew();



        $setings->name = $request->name;
        $setings->save();

        return response()->json($setings);
    }
}
