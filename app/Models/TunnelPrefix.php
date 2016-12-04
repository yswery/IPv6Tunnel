<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TunnelPrefix extends Model
{
    protected $table = 'tunnel_prefixes';

    public function tunnel()
    {
        return $this->belongsTo(Tunnel::class);
    }
}
