<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TunnelServer extends Model
{
    protected $table = 'tunnel_servers';

    protected $fillable = [
        'address',
        'name',
        'country_code',
        'city',
        'ssh_password',
        'ssh_port',
    ];

    protected $hidden = [
        'ssh_password',
    ];

    public function prefixes()
    {
        return $this->hasMany(TunnelPrefix::class);
    }

    public function tunnels()
    {
        return $this->hasMany(Tunnel::class);
    }

}
