<?php

namespace App\Services;

use App\Models\Tunnel;
use App\Models\TunnelPrefix;
use App\Models\TunnelServer;
use App\Models\User;
use App\Utils\IpUtils;

class TunnelService
{

    protected $ripeService;
    protected $ipUtils;

    public function __construct()
    {
        $this->ripeService = new RipeService();
        $this->ipUtils     = new IpUtils();
    }

    // Allocate a tunnel address and prefix
    public function createTunnelCombo(User $user, TunnelServer $tunnelServer, $remoteAddress, $cidrSize = 48)
    {
        // Get available address
        $tunnel = Tunnel::whereNull('user_id')->where('tunnel_server_id', $tunnelServer->id)->first();

        // Set the remote address and user ID
        $tunnel->remote_v4_address = $remoteAddress;
        $tunnel->user_id           = $user->id;
        $tunnel->save();

        // Provision the tunnel address and interface on local tunnel server
        \SSH::into($tunnelServer->name)->run([
            'ip tunnel add ' . $tunnel->local_interface . ' mode sit remote ' . $tunnel->remote_v4_address . ' local ' . $tunnel->local_v4_address . ' ttl 255',
            'ip link set ' . $tunnel->local_interface . ' up',
            'ip link set dev ' . $tunnel->local_interface . ' mtu ' . $tunnel->mtu_size,
            'ip addr add ' . $tunnel->local_tunnel_address . '/64 dev ' . $tunnel->local_interface,
        ]);

        $tunnelPrefix = $this->allocateTunnelPrefix($tunnelServer, $tunnel, $cidrSize);

        return [
            'tunnel'        => $tunnel,
            'tunnel_prefix' => $tunnelPrefix,
            'tunnel_server' => $tunnelServer,
        ];
    }

    // Allocate prefix and route through to an existant tunnel
    public function allocateTunnelPrefix(TunnelServer $tunnelServer, Tunnel $tunnel, $cidrSize = 48)
    {
        // Get available prefix
        $tunnelPrefix = TunnelPrefix::whereNull('user_id')->where('cidr', $cidrSize)->where('tunnel_server_id', $tunnelServer->id)->first();

        $tunnelPrefix->user_id   = $tunnel->user_id;
        $tunnelPrefix->tunnel_id = $tunnel->id;
        $tunnelPrefix->save();

        // Route the prefix through the existing tunnel address
        \SSH::into($tunnelServer->name)->run([
            'ip route add ' . $tunnelPrefix->address . '/' . $tunnelPrefix->cidr . ' dev ' . $tunnel->local_interface,
        ]);

        // Set the default whois object
        $this->ripeService->createPrefixWhois($tunnelPrefix);

        return $tunnelPrefix;
    }

    // Remove the tunnel prefix from a user and tunnel
    public function removeTunnel(Tunnel $tunnel)
    {
        // Remove all tunnel prefixes associated with the tunnel
        foreach ($tunnel->prefixes as $tunnelPrefix) {
            $this->removeTunnelPrefix($tunnelPrefix);
        }

        // Provision the tunnel address and interface on local tunnel server
        \SSH::into($tunnel->server->name)->run([
            'ip addr del ' . $tunnel->local_tunnel_address . '/64 dev ' . $tunnel->local_interface,
            'ip link set ' . $tunnel->local_interface . ' down',
            'ip tunnel del ' . $tunnel->local_interface,
        ]);

        $tunnel->remote_v4_address = null;
        $tunnel->user_id           = null;
        $tunnel->save();

        // Clean up and remove old whois object
        $this->ripeService->deletePrefixWhois($tunnelPrefix);

        return $tunnel;
    }

    // Remove the tunnel prefix from a user and tunnel
    public function removeTunnelPrefix(TunnelPrefix $tunnelPrefix)
    {
        // Remove the static route from the tunnel server node
        \SSH::into($tunnelPrefix->server->name)->run(
            'ip route del ' . $tunnelPrefix->address . '/' . $tunnelPrefix->cidr . ' dev ' . $tunnelPrefix->tunnel->local_interface
        );

        // Reset the prefix to null and add back to pool
        $tunnelPrefix->user_id   = null;
        $tunnelPrefix->tunnel_id = null;
        $tunnelPrefix->save();

        return $tunnelPrefix;
    }

    // Loop through all pools and gets the next available free prefix available from the pool
    private function getNextAvailablePrefix($tunnelServer, $prefixSizeToAllocate)
    {
        $ipv6IpCount = $this->ipUtils->IPv6cidrIpCount();

        // Loop through all the pools
        foreach ($tunnelServer->prefixPools as $prefixPool) {

            // Get the IP dec range for our total pool
            $poolAddressRange = $this->ipUtils->cidr2range($prefixPool->address . '/' . $prefixPool->cidr, $returnInDecimal = true);

            // Get the IP dec address range for our prefix that we wish to allocate
            $addressRange = $this->ipUtils->cidr2range($prefixPool->address . '/' . $prefixSizeToAllocate, $returnInDecimal = true);

            while (true) {

                // Lets make sure that our desired end address is not outside the pool end
                if ($addressRange['end'] > $poolAddressRange['end']) {
                    // break the while loop in order to allow the above pool foreach to work
                    break;
                }

                // Check if our prefix is inside another existing prefix
                $existingPrefix = TunnelPrefix::where('ip_dec_start', '>=', $addressRange['start'])
                                                ->where('ip_dec_end', '<=', $addressRange['end'])
                                                ->first();

                // since there is a prefix inside our needed one, lets increment a full self subnet (and try again)
                if (is_null($existingPrefix) !== true) {
                    $addressRange['start'] = bcadd($addressRange['start'], $ipv6IpCount[$prefixSizeToAllocate]);
                    $addressRange['end']   = bcadd($addressRange['end'], $ipv6IpCount[$prefixSizeToAllocate]);
                    continue;
                }

                // ===========================

                // check if our start or end addresses is between another existing prefix
                $existingPrefixes = TunnelPrefix::where(function ($q) use ($addressRange) {
                                        $q->where('ip_dec_start', '<=', $addressRange['start'])
                                            ->where('ip_dec_end', '>=', $addressRange['start']);
                                    })->orWhere(function ($q) use ($addressRange) {
                                        $q->where('ip_dec_start', '<=', $addressRange['end'])
                                            ->where('ip_dec_end', '>=', $addressRange['end']);
                                    })->get();

                // if there are already overlapping prefixes
                if ($existingPrefixes->count() > 0) {
                    // Loop through all the existing prefixes and find the largest end address to increment on
                    $largestEndAddress = $addressRange['end'];
                    foreach ($existingPrefixes as $existingPrefix) {
                        if ($largestEndAddress < $existingPrefix->ip_dec_end) {
                            $largestEndAddress = $existingPrefix->ip_dec_end;
                        }
                    }

                    $addDecOffset          = bcadd(bcsub($largestEndAddress, $addressRange['end']), 1);
                    $addressRange['start'] = bcadd($addressRange['start'], $addDecOffset);
                    $addressRange['end']   = bcadd($addressRange['end'], $addDecOffset);
                    continue;
                }

                return [
                    'pool_id'           => $prefixPool->id,
                    'prefix'            => $this->ipUtils->range2cidr($addressRange['start'], $addressRange['end']),
                    'start_dec_address' => $addressRange['start'],
                    'end_dec_address'   => $addressRange['start'],
                ];
            }

        }

        // If we are return false this means there was no space available for our desired address
        return false;
    }

}
