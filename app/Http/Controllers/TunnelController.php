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
            'tunnel-server-id' => 'required',
            'remote-ipv4'      => 'required|ip',
        ]);

        $tunnelServerId    = $request->get('tunnel-server-id');
        $remoteIpv4Address = $request->get('remote-ipv4');

        $newTunnel = $tunnelService->createTunnelCombo($user, TunnelServer::find($tunnelServerId), $remoteIpv4Address);

        return route('tunnel.details', $newTunnel->id);
    }

    public function tunnelList()
    {
        $user    = Auth::user();
        $tunnels = Tunnel::where('user_id', $user->id)->get();

        return view('tunnels.list')->with('tunnels', $tunnels);
    }

    public function tunnelDetails($tunnelId)
    {
        return "Shows all details and configs of the tunnel";
    }
}
