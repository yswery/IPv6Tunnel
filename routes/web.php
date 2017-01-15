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


    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
        Route::get('/prefix-pool', ['as' => 'admin.prefixes-pool.index', 'uses' => 'PrefixPoolController@index']);
        Route::get('/tunnel-servers', ['as' => 'admin.tunnel-servers.index', 'uses' => 'TunnelServerController@index']);

    });





    Route::get('/home', 'HomeController@index');

    Route::get('/new-tunnel', ['as' => 'new-tunnel', 'uses' => 'TunnelController@newTunnel']);
    Route::post('/new-tunnel', ['as' => 'new-tunnel', 'uses' => 'TunnelController@create']);

    Route::get('/tunnels', ['as' => 'tunnels.list', 'uses' => 'TunnelController@tunnelList']);
    Route::get('/tunnels/{tunnel_id}', ['as' => 'tunnels.details', 'uses' => 'TunnelController@tunnelDetails']);
    Route::post('/tunnels/{tunnel_id}/prefix', ['as' => 'tunnels.add-prefix', 'uses' => 'TunnelController@addPrefix']);
    Route::get('/tunnels/{tunnel_id}/delete', ['as' => 'tunnels.delete', 'uses' => 'TunnelController@delete']);

    Route::group(['prefix' => 'ajax'], function (){
        // Add an ajax endpoint to query the name for the prefix and also the ability to set it?
    });

});
