<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Wifi extends Model
{
    protected $fillable = [
        'found_at', 'latitude', 'longitude', 'bssid', 'ssid', 'encryption', 'publicip'
    ];

    protected $casts = [
        'found_at' => 'timestamp',
        'latitude' => 'double',
        'longitude' => 'double',
        'bssid' => 'string',
        'ssid' => 'string',
        'encryption' => 'int',
        'publicip' => 'string'
    ];
}