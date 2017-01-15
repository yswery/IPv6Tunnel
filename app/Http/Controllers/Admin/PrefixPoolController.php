<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\PrefixPool;

class PrefixPoolController extends Controller
{
    public function index()
    {
        $prefixPool = PrefixPool::with('server')->get();

        return view('admin.prefix-pool.index')->with('prefixPool', $prefixPool);
    }
}
