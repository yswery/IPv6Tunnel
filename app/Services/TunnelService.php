<?php

namespace App\Services;

use App\Models\TunnelAddress;
use App\Models\TunnelPrefix;

class TunnelService
{

    // Allocate a tunnel address and prefix
    public function createTunnelCombo($cidrSize = 48, $userId, $remoteAddress)
    {
        // Get available address
        $tunnelAddress = TunnelAddress::whereNull('user_id')->first();

        // Set the remote address and user ID
        $tunnelAddress->remote_address = $remoteAddress;
        $tunnelAddress->user_id        = $userId;
        $tunnelAddress->save();

        $tunnelPrefix = $this->allocateTunnelPrefix($cidrSize, $tunnelAddress);
        // TO DO: Provision the tunnel address and interface on local tunnel server

        return [
            'tunnel_address' => $tunnelAddress,
            'tunnel_prefix'  => $tunnelPrefix,
        ];
    }

    // Allocate prefix and route through to an existant tunnel
    public function allocateTunnelPrefix($cidrSize = 48, $tunnelAddress)
    {
        // Get available prefix
        $tunnelPrefix = TunnelPrefix::whereNull('user_id')->where('cidr', $cidrSize)->first();

        $tunnelPrefix->user_id           = $tunnelAddress->user_id;
        $tunnelPrefix->tunnel_address_id = $tunnelAddress->id;
        $tunnelPrefix->save();

        // TO DO: Route the prefix through the existing tunnel address

        return $tunnelPrefix;
    }

}
