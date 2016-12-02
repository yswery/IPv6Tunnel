<?php

namespace App\Services;

use App\Models\Tunnel;
use App\Models\TunnelPrefix;

class TunnelService
{

    // Allocate a tunnel address and prefix
    public function createTunnelCombo($userId, $remoteAddress, $cidrSize = 48)
    {
        // Get available address
        $tunnel = Tunnel::whereNull('user_id')->first();

        // Set the remote address and user ID
        $tunnel->remote_v4_address = $remoteAddress;
        $tunnel->user_id           = $userId;
        $tunnel->save();

        // Provision the tunnel address and interface on local tunnel server
        \SSH::run([
            'ip tunnel add ' . $tunnel->local_interface . ' mode sit remote ' . $tunnel->remote_v4_address . ' local ' . $tunnel->local_v4_address . ' ttl 255',
            'ip link set ' . $tunnel->local_interface . ' up',
            'ip addr add ' . $tunnel->local_tunnel_address . '/64 dev ' . $tunnel->local_interface,
        ]);

        $tunnelPrefix = $this->allocateTunnelPrefix($cidrSize, $tunnel);

        return [
            'tunnel'        => $tunnel,
            'tunnel_prefix' => $tunnelPrefix,
        ];
    }

    // Allocate prefix and route through to an existant tunnel
    public function allocateTunnelPrefix($cidrSize = 48, $tunnel)
    {
        // Get available prefix
        $tunnelPrefix = TunnelPrefix::whereNull('user_id')->where('cidr', $cidrSize)->first();

        $tunnelPrefix->user_id   = $tunnel->user_id;
        $tunnelPrefix->tunnel_id = $tunnel->id;
        $tunnelPrefix->save();

        // Route the prefix through the existing tunnel address
        \SSH::run([
            'ip route add ' . $tunnelPrefix->address . '/' . $tunnelPrefix->cidr . ' dev ' . $tunnel->local_interface,
        ]);

        return $tunnelPrefix;
    }

}
