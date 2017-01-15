<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrefixPool extends Model
{
    protected $table = 'prefix_pool';

    public function prefixes()
    {
        return $this->hasMany(TunnelPrefix::class);
    }

    public function server()
    {
        return $this->belongsTo(TunnelServer::class, 'tunnel_server_id');
    }

}
