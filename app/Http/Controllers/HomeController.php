<?php

namespace App\Http\Controllers;

use App\Models\Tunnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('home')->with('tunnels', $user->tunnels);
    }
}
