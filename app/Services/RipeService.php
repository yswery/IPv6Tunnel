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
        $this->client = new Client();
    }

    public function deletePrefixWhois(TunnelPrefix $prefix)
    {
        // Delete a prefix object
        $urlKey = 'inet6num/' . $prefix->address . '/' . $prefix->cidr . '.json';
        return $this->makeRequest($urlKey, 'DELETE');
    }

    public function createPrefixWhois(TunnelPrefix $prefix)
    {
        // Create a prefix object with default names and allocation details
    }

    public function changePrefixWhois(TunnelPrefix $prefix, $name, $country, $person = null)
    {
        // Give the ability for the user to set a name and country on the object
    }

    private function makeRequest($urlKey, $httpType = 'GET', $dataArray = [])
    {
        $fullUrl  = $this->apiUrl . $urlKey . '?password=' . $this->mntPassword;
        $baseBody = [
            'objects' => [
                'object' => [
                    [
                        'source'     => [
                            'id' => 'RIPE',
                        ],
                        'attributes' => [
                            'attribute' => [
                                // Here is a list of all arttibutes
                                //                                [
                                //    'name' => 'mnt',
                                //    'value' => 'MNTNDND',
                                // ],
                            ],
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
