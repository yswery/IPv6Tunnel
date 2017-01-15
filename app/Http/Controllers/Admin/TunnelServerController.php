<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TunnelServer;

class TunnelServerController extends Controller
{
    public function index()
    {
        $tunnelServers = TunnelServer::all();

        return view('admin.tunnel-servers.index')
            ->with('tunnelServers', $tunnelServers);
    }

    function new () {
        // create the prefix here and return resposne in json
    }
}
