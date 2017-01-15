<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrefixPool;
use App\Models\TunnelServer;

class PrefixPoolController extends Controller
{
    public function index()
    {
        $prefixPool    = PrefixPool::with('server')->get();
        $tunnelServers = TunnelServer::all();

        return view('admin.prefix-pool.index')
            ->with('prefixPool', $prefixPool)
            ->with('tunnelServers', $tunnelServers);
    }

    function new () {
        // create the prefix here and return resposne in json
    }
}
