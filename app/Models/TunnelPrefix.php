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

    public function server()
    {
        return $this->belongsTo(TunnelServer::class);
    }

    public function getDnsServersAttribute()
    {
        return json_decode($this->dns_servers_json, true);
    }

    public function setDnsServersAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            return $this->attributes['dns_servers_json'] = json_encode($value);
        }

        json_decode($value);

        if (json_last_error() == JSON_ERROR_NONE) {
            $this->attributes['dns_servers_json'] = $value;
        } else {
            $this->attributes['dns_servers_json'] = json_encode($value);
        }
    }
}
