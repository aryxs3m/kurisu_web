<?php

namespace App\Http\Controllers;

use App\Models\Diagnostics;
use App\Models\Wifi;
use Illuminate\Http\Request;

class DesktopController extends Controller
{
    public function wifiList()
    {
        return Wifi::all();
    }

    public function diagData()
    {
        return response()->json([
            'lastsync' => Wifi::latest()->first()->created_at->toDateTimeString(),
            'lastbattery' => Diagnostics::latest()->first()->battery_level
        ]);
    }

    public function diagHistory()
    {
        return Diagnostics::all();
    }
}
