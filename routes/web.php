<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', 'HomeController@index');

    Route::get('/new-tunnel', ['as' => 'new-tunnel', 'uses' => 'TunnelController@newTunnel']);
    Route::post('/new-tunnel', ['as' => 'new-tunnel', 'uses' => 'TunnelController@create']);

    Route::get('/tunnels', ['as' => 'tunnel.list', 'uses' => 'TunnelController@tunnelList']);
    Route::get('/tunnels/{tunnel_id}', ['as' => 'tunnel.details', 'uses' => 'TunnelController@tunnelDetails']);

});
