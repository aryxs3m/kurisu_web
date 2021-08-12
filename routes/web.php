<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return view('index');
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/import', 'KurisuMasterController@import');
    $router->post('/diagnostics', 'KurisuMasterController@saveDiagnostics');

    $router->get('/wifi-list', 'DesktopController@wifiList');
    $router->get('/diag-data', 'DesktopController@diagData');
});
