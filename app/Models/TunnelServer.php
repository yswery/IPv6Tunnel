<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TunnelServer extends Model
{
    protected $table = 'tunnel_servers';

    public function prefixes()
    {
        return $this->hasMany(TunnelPrefix::class);
    }

    public function tunnels()
    {
        return $this->hasMany(Tunnel::class);
    }

}
