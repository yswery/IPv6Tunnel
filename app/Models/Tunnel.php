<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tunnel extends Model
{
    protected $table = 'tunnels';

    public function prefixes()
    {
        return $this->hasMany(TunnelPrefix::class);
    }

    public function server()
    {
        return $this->belongsTo(TunnelServer::class, 'tunnel_server_id');
    }

    public function getRoutedPrefixesAttribute()
    {
        return $this->prefixes()->where('routed_prefix', true)->get();
    }

}
