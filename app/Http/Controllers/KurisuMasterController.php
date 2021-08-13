<?php

namespace App\Http\Controllers;

use App\Models\Diagnostics;
use App\Models\Setting;
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

    public function getConfig()
    {
        try
        {
            $EEPROM_SETTINGS_GET_PUBLICIP = Setting::where('key', 'EEPROM_SETTINGS_GET_PUBLICIP')->first()->value;
            $EEPROM_SETTINGS_AUTO_SYNC = Setting::where('key', 'EEPROM_SETTINGS_AUTO_SYNC')->first()->value;
            $EEPROM_SETTINGS_AUTO_SYNC_INTERVAL = Setting::where('key', 'EEPROM_SETTINGS_AUTO_SYNC_INTERVAL')->first()->value;

            return "{$EEPROM_SETTINGS_GET_PUBLICIP};{$EEPROM_SETTINGS_AUTO_SYNC};{$EEPROM_SETTINGS_AUTO_SYNC_INTERVAL};";
        } catch (\Error $exception) {
            return "0;0;120;";
        }
    }
}
