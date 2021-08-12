<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Diagnostics extends Model
{
    protected $fillable = [
        'battery_level'
    ];

    protected $casts = [
        'battery_level' => 'double'
    ];
}