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
        $tunnelServer = TunnelServer::create($request->all());

        return [
            'status' => 'ok',
            'status_message' => 'Query was successful',
            'data' => $tunnelServer,
        ];
    }

    public function testSSH($server_id)
    {
        $tunnelServer = TunnelServer::find($server_id);

        $output = $tunnelServer->sshExec('whoami');

        if ($output == 'root') {
            $data = [
                'status' => 'ok',
                'status_message' => 'Query was successful',
                'data' => null,
            ];
        } else {
            $data = [
                'status' => 'error',
                'status_message' => 'Could not SSH into server',
                'data' => null,
            ];
        }

        return $data;
    }
}
