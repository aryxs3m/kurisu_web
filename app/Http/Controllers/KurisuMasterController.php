<?php

namespace App\Http\Controllers;

use App\Models\Diagnostics;
use App\Models\Wifi;
use Illuminate\Http\Request;

class KurisuMasterController extends Controller
{
    public function import(Request $request)
    {
        $validated = $this->validate($request, [
            'found_at' => 'date',
            'latitude' => 'numeric|required',
            'longitude' => 'numeric|required',
            'bssid' => 'string',
            'ssid' => 'string|required',
            'encryption' => 'int|required',
            'publicip' => 'string'
        ]);

        return Wifi::create($validated);
    }

    public function saveDiagnostics(Request $request)
    {
        $validated = $this->validate($request, [
            'battery_level' => 'numeric|required',
        ]);

        return Diagnostics::create($validated);
    }
}
