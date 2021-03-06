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
        Route::post('/prefix-pool/create', ['as' => 'admin.prefixes-pool.create', 'uses' => 'PrefixPoolController@create']);

        Route::get('/tunnel-servers', ['as' => 'admin.tunnel-servers.index', 'uses' => 'TunnelServerController@index']);
        Route::get('/tunnel-servers/{server_id}/test-ssh', ['as' => 'admin.tunnel-servers.test-ssh', 'uses' => 'TunnelServerController@testSSH']);
        Route::post('/tunnel-servers/create', ['as' => 'admin.tunnel-servers.create', 'uses' => 'TunnelServerController@create']);

    });

    Route::get('/tunnels', ['as' => 'tunnels.index', 'uses' => 'TunnelController@index']);
    Route::post('/tunnels/create', ['as' => 'tunnels.create', 'uses' => 'TunnelController@create']);










    Route::get('/home', 'HomeController@index');

    Route::get('/new-tunnel', ['as' => 'new-tunnel', 'uses' => 'TunnelController@newTunnel']);
    Route::post('/new-tunnel', ['as' => 'new-tunnel', 'uses' => 'TunnelController@create']);

    Route::get('/tunnels/{tunnel_id}', ['as' => 'tunnels.details', 'uses' => 'TunnelController@tunnelDetails']);
    Route::post('/tunnels/{tunnel_id}/edit', ['as' => 'tunnels.edit', 'uses' => 'TunnelController@editTunnel']);
    Route::post('/tunnels/{tunnel_id}/prefix', ['as' => 'tunnels.add-prefix', 'uses' => 'TunnelController@addPrefix']);
    Route::get('/tunnels/{tunnel_id}/delete', ['as' => 'tunnels.delete', 'uses' => 'TunnelController@delete']);

    Route::group(['prefix' => 'ajax'], function () {
        Route::post('/edit-prefix', ['uses' => 'TunnelController@editPrefix']);
    });

});
