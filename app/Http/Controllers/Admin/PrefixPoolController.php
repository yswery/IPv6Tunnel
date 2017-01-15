<?php

namespace App\Http\Controllers\Admin;


use App\Models\PrefixPool;

class PrefixPoolController extends Controller
{
    public function index()
    {
        $prefixPool = PrefixPool::with('server')->all();

        return view('admin.prefix-pool.index')->with('prefixPool', $prefixPool);
    }
}
