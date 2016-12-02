<?php

namespace App\Services;

use App\Models\TunnelAddress;
use App\Models\TunnelPrefix;

class TunnelService
{

    public function allocateTunnelPrefix($cidrSize = 48, $userId, $remoteAddress)
    {
        // Get available prefix
        $tunnelPrefix = TunnelPrefix::whereNull('user_id')->where('cidr', $cidrSize)->first();

        // Get available address
        $tunnelAddress = TunnelAddress::whereNull('user_id')->first();

        // Set the remote address and user ID
        $tunnelAddress->remote_address = $remoteAddress;
        $tunnelAddress->user_id        = $userId;
        $tunnelAddress->save();

        $tunnelPrefix->user_id           = $userId;
        $tunnelPrefix->tunnel_address_id = $tunnelAddress->id;
        $tunnelPrefix->save();

        return [
            'tunnel_prefix'  => $tunnelPrefix,
            'tunnel_address' => $tunnelAddress,
        ];
    }
}
