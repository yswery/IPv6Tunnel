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

        $tunnelPrefix = $this->allocateTunnelPrefix($cidrSize, $tunnel);
        // TO DO: Provision the tunnel address and interface on local tunnel server

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

        // TO DO: Route the prefix through the existing tunnel address

        return $tunnelPrefix;
    }

}
