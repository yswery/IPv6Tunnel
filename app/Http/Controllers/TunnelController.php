<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPrefix;
use App\Http\Requests\EditTunnel;
use App\Http\Requests\StoreTunnel;
use App\Models\Tunnel;
use App\Models\TunnelPrefix;
use App\Models\TunnelServer;
use App\Services\RipeService;
use App\Services\TunnelService;
use Illuminate\Support\Facades\Auth;

class TunnelController extends Controller
{
    public function newTunnel()
    {
        return view('tunnels.create-new-tunnel')->with('tunnelServers', TunnelServer::all());
    }

    public function create(StoreTunnel $request, TunnelService $tunnelService)
    {
        $user = Auth::user();

        $tunnelServerId    = $request->get('tunnel_server_id');
        $remoteIpv4Address = $request->get('remote_v4_address');

        $newTunnel = $tunnelService->createTunnelCombo($user, TunnelServer::find($tunnelServerId), $remoteIpv4Address);

        return [
            'status'         => 'ok',
            'status_message' => 'Query was successful',
            'data'           => $newTunnel,
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

    public function editTunnel(TunnelService $tunnelService, EditTunnel $request, $tunnelId)
    {
        $tunnel                    = Tunnel::find($tunnelId);

        $tunnel->remote_v4_address = $request->get('remote_v4_address');
        $tunnel->mtu_size          = $request->get('mtu_size');
        $tunnel->save();

        $tunnelService->reprovisionTunnel($tunnel);

        return [
            'status'         => 'ok',
            'status_message' => 'Query was successful',
            'data'           => $tunnel,
        ];
    }

    public function delete(TunnelService $tunnelService, $tunnelId)
    {
        $tunnel = Tunnel::find($tunnelId);

        $tunnelService->removeTunnel($tunnel);

        return redirect()->route('tunnels.index');
    }

    public function addPrefix(TunnelService $tunnelService, $tunnelId)
    {
        $tunnel = Tunnel::find($tunnelId);

        $tunnelService->allocateTunnelPrefix($tunnel->server, $tunnel);

        return redirect()->route('tunnels.details', $tunnel->id);
    }

    public function editPrefix(EditPrefix $request, RipeService $ripeService)
    {
        $user   = Auth::user();
        $prefix = TunnelPrefix::where('id', $request->get('prefix_id'))->where('user_id', $user->id)->first();

        $prefix->name = $request->get('name');
        $prefix->save();

        $ripeService->changePrefixWhois($prefix, $prefix->country_code, $prefix->name);

        return [
            'status'         => 'ok',
            'status_message' => 'Query was successful',
            'data'           => $prefix,
        ];
    }
}
