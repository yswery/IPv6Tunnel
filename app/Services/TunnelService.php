<?php

namespace App\Services;


use App\Models\TunnelAddress;
use App\Models\TunnelPrefix;

class TunnelService
{

    public function allocateTunnelPrefix($cidrSize = 48, $remoteAddress)
    {
        // Get available prefix
        $tunnelPreifx = TunnelPrefix::whereNull('user_id')->where('cidr', $cidrSize)->first();

        // Get available address
        $tunnelAddress = TunnelAddress::whereNull('user_id')->first();



    }
}
