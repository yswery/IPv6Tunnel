<?php

namespace App\Services;

use App\Models\TunnelPrefix;
use GuzzleHttp\Client;

class DnsService
{

    protected $dnsApiUrl;
    protected $dnsApiKey;
    protected $client;

    public function __construct()
    {
        $this->dnsApiKey = env('PDNS_API_KEY');
        $this->dnsApiUrl = env('PDNS_URL');
        $this->client    = new Client();
    }

    public function setDnsServers(TunnelPrefix $prefix)
    {
        $dnsQuery['rrsets'] = [];

        $nsQuery = [
            'name'       => $this->getPtrZone($prefix->address . '/' . $prefix->cidr),
            'type'       => 'NS',
            'ttl'        => 3600,
            'changetype' => 'REPLACE',
            'records'    => [],
        ];

        // Loop through all DNS servers and set them in pDNS
        foreach ($prefix->dns_servers as $dnsServer) {
            $nsQuery['records'][] = [
                'content'  => $dnsServer,
                'disabled' => false,
            ];
        }

        $dnsQuery['rrsets'][] = $nsQuery;

        return $this->makeRequest('zones/' . $prefix->rdns_zone, 'PATCH', $dnsQuery);
    }

    public function makeRequest($urlKey, $httpType = 'GET', $dataArray = [])
    {
        $fullUrl = $this->dnsApiUrl . $urlKey;
        dump($dataArray);
        // Make the HTTP Request
        try {
            $request = $this->client->request($httpType, $fullUrl, [
                'debug'   => true,
                'json'    => $dataArray,
                'headers' => [
                    'Accept'    => '*',
                    'X-API-Key' => $this->dnsApiKey,
                ],
            ]);
            $response = $request->getBody(true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse()->getBody(true);
        }

        return json_decode($response);
    }

    private function hexintval($n)
    {
        return intval($n, 16);
    }

    private function arpa_field_str($n, $bits)
    {
        $ret = '';
        // apply mask for non-nibble boundaries
        $n &= ((1 << $bits) - 1) << (16 - $bits);
        $a = unpack("C*", pack("S", $n));
        if ($bits % 4 != 0) {
            $bits += 4;
        }

        $count = (int) ($bits / 4);

        // extract 4 4bit values from 16bit integral
        if ($count >= 4) {
            $ret .= dechex($a[1] & 0xF) . '.';
        }

        if ($count >= 3) {
            $ret .= dechex($a[1] >> 4) . '.';
        }

        if ($count >= 2) {
            $ret .= dechex($a[2] & 0xF) . '.';
        }

        if ($count >= 1) {
            $ret .= dechex($a[2] >> 4) . '.';
        }

        return $ret;
    }

    private function ipv6_arpa_str_fmt($fields, $cidr)
    {
        $ret = '';

        // round down CIDR to multiple of 16 for indexing
        $i = ($cidr & -16);
        $cidr -= $i; // set to remaining bits
        $i /= 16; // create index value
        $n = $i; // assign to last index

        while (--$i >= 0) {
            $ret .= $this->arpa_field_str($fields[$i], 16);
        }

        // $cidr is now the # of bits remaining from CIDR
        // $n = index of 16bit integer
        if ($cidr > 0) {
            $ret = $this->arpa_field_str($fields[$n], $cidr) . $ret;
        }
        return $ret . 'ip6.arpa.';

    }

    private function getPtrZone($prefix)
    {
        $tokens = explode('::', $prefix);
        if (count($tokens) == 2) {
            $cidr = intval(substr($tokens[1], 1));
            if ($cidr != 0) {
                /* create an array from hex fields in ipv6 prefix padded to
                8 integer elements for support up to /128 (8 * 16bits = 128).
                note: only least significant 16bits are used for each array
                integer element to represent each of the 8 16bit fields. */
                $fields = array_map([$this, 'hexintval'], explode(':', $tokens[0]));
                $fields = array_pad($fields, 8, 0);
                return $this->ipv6_arpa_str_fmt($fields, $cidr);
            }
        }
        return null;
    }

}
