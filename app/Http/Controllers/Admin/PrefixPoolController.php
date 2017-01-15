<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrefixPool;
use App\Models\PrefixPool;
use App\Models\TunnelServer;

class PrefixPoolController extends Controller
{
    public function index()
    {
        $prefixPool    = PrefixPool::with('server')->get();
        $tunnelServers = TunnelServer::all();

        return view('admin.prefix-pool.index')
            ->with('prefixPool', $prefixPool)
            ->with('tunnelServers', $tunnelServers);
    }

    public function create(StorePrefixPool $request) {
        $prefix = PrefixPool::create($request->all());

        return [
            'status' => 'ok',
            'status_message' => 'Query was successful',
            'data' => $prefix,
        ];
    }
}
