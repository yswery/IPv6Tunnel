<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTunnelServers;
use App\Models\TunnelServer;

class TunnelServerController extends Controller
{
    public function index()
    {
        $tunnelServers = TunnelServer::all();

        return view('admin.tunnel-servers.index')
            ->with('tunnelServers', $tunnelServers);
    }

    public function create(StoreTunnelServers $request) {
        $tunnel = TunnelServer::create($request->all());

        return [
            'status' => 'ok',
            'status_message' => 'Query was successful',
            'data' => $tunnel,
        ];
    }
}
