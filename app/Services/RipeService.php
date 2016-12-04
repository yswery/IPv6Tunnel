<?php

namespace App\Services;

use App\Models\TunnelPrefix;
use GuzzleHttp\Client;

class RipeService
{

    protected $apiUrl = 'https://rest.db.ripe.net/ripe/';
    protected $mntPassword;
    protected $client;

    public function __construct()
    {
        $this->mntPassword = env('RIPE_MNT_PASSWORD');
        $this->client      = new Client();
    }

    function deletePrefixWhois(TunnelPrefix $prefix)
    {
        // Delete a prefix object
        $urlKey = 'inet6num/' . $prefix->address . '/' . $prefix->cidr;
        return $this->makeRequest($urlKey, 'DELETE');
    }

    function createPrefixWhois(TunnelPrefix $prefix, $country = 'NZ', $name = null)
    {
        $descr = $name ?: 'Tunnelled Prefix (TID: '.$prefix->tunnel->id.')';
        $netname = $name ? strtoupper(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", "", $name))) : 'TUNNEL-TID-' . $prefix->tunnel->id;

        // Create a prefix object with default names and allocation details
        $dataArray = [
            [
                'name'  => 'inet6num',
                'value' => $prefix->address . '/' . $prefix->cidr,
            ],
            [
                'name'  => 'netname',
                'value' => $netname,
            ],
            [
                'name'  => 'descr',
                'value' => $descr,
            ],
            [
                'name'  => 'country',
                'value' => $country,
            ],
            [
                'name'  => 'status',
                'value' => 'ASSIGNED',
            ],
            [
                'name'  => 'admin-c',
                'value' => 'AB31884-RIPE',
            ],
            [
                'name'  => 'tech-c',
                'value' => 'AB31884-RIPE',
            ],
            [
                'name'  => 'mnt-by',
                'value' => 'ADAMBB-MNT',
            ],
            [
                'name'  => 'source',
                'value' => 'RIPE',
            ],
        ];

        $uriKey = 'inet6num/' . $prefix->address . '/' . $prefix->cidr;
        return $this->makeRequest($uriKey, 'PUT', $dataArray);
    }

    function changePrefixWhois(TunnelPrefix $prefix, $country, $name)
    {
        // Delete old prefix
        $this->deletePrefixWhois($prefix);

        // Cretae new prefix
        return $this->createPrefixWhois($prefix, $country, $name);
    }

    function makeRequest($urlKey, $httpType = 'GET', $dataArray = [])
    {
        $fullUrl  = $this->apiUrl . $urlKey . '.json?password=' . $this->mntPassword;

        $baseBody = [
            'objects' => [
                'object' => [
                    [
                        'source'     => [
                            'id' => 'RIPE',
                        ],
                        'attributes' => [
                            'attribute' => $dataArray,
                        ],

                    ],
                ],
            ],
        ];

        // Make the HTTP Request
        try {
            $request = $this->client->request($httpType, $fullUrl, [
                'debug'   => false,
                'json'    => $baseBody,
                'headers' => [
                    'Accept' => '*',
                ],
            ]);
            $response = $request->getBody(true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse()->getBody(true);
        }

        return json_decode($response);
    }
}
