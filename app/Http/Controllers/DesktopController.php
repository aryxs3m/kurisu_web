<?php

namespace App\Http\Controllers;

use App\Models\Diagnostics;
use App\Models\Setting;
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

    public function saveConfig(Request $request)
    {
        $validated = $this->validate($request, [
            'publicip' => 'integer',
            'autosync' => 'integer',
            'autosync_interval' => 'integer|min:0|max:120'
        ]);

        Setting::truncate();
        Setting::create([
            'key' => 'EEPROM_SETTINGS_GET_PUBLICIP',
            'value' => $validated['publicip']
        ]);
        Setting::create([
            'key' => 'EEPROM_SETTINGS_AUTO_SYNC',
            'value' => $validated['autosync']
        ]);
        Setting::create([
            'key' => 'EEPROM_SETTINGS_AUTO_SYNC_INTERVAL',
            'value' => $validated['autosync_interval']
        ]);

        return response()->json(['success' => true]);
    }

    public function getConfig()
    {
        try {
            return response()->json([
                'publicip' => Setting::where('key', 'EEPROM_SETTINGS_GET_PUBLICIP')->first()->value,
                'autosync' => Setting::where('key', 'EEPROM_SETTINGS_AUTO_SYNC')->first()->value,
                'autosync_interval' => Setting::where('key', 'EEPROM_SETTINGS_AUTO_SYNC_INTERVAL')->first()->value
            ]);
        } catch (\Error $exception) {
            return response()->json([
                'publicip' => 0,
                'autosync' => 0,
                'autosync_interval' => 60
            ]);
        }
    }
}
