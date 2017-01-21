<?php

namespace App\Http\Controllers;

use App\Models\Tunnel;
use App\Models\TunnelServer;
use App\Services\TunnelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TunnelController extends Controller
{
    public function newTunnel()
    {
        return view('tunnels.create-new-tunnel')->with('tunnelServers', TunnelServer::all());
    }

    public function create(Request $request, TunnelService $tunnelService)
    {
        $user = Auth::user();

        $this->validate($request, [
            'tunnel_server_id' => 'required',
            'remote_v4_address'      => 'required|ip',
        ]);

        $tunnelServerId    = $request->get('tunnel_server_id');
        $remoteIpv4Address = $request->get('remote_v4_address');

        $newTunnel = $tunnelService->createTunnelCombo($user, TunnelServer::find($tunnelServerId), $remoteIpv4Address);

        return [
            'status' => 'ok',
            'status_message' => 'Query was successful',
            'data' => $newTunnel,
        ];
    }

    public function index()
    {
        $user          = Auth::user();
        $tunnels       = Tunnel::with('server')->where('user_id', $user->id)->get();
        $tunnelServers = TunnelServer::all();

        return view('tunnels.index')->with('tunnels', $tunnels)->with('tunnelServers', $tunnelServers);
    }

    public function tunnelDetails($tunnelId)
    {
        $tunnel = Tunnel::find($tunnelId);

        return view('tunnels.details')->with('tunnel', $tunnel);
    }

    public function delete(TunnelService $tunnelService, $tunnelId)
    {
        $tunnel = Tunnel::find($tunnelId);

        $tunnelService->removeTunnel($tunnel);

        return redirect()->route('tunnels.list');
    }

    public function addPrefix(TunnelService $tunnelService, $tunnelId)
    {
        $tunnel = Tunnel::find($tunnelId);

        $tunnelService->allocateTunnelPrefix($tunnel->server, $tunnel);

        return redirect()->route('tunnels.details', $tunnel->id);
    }
}
