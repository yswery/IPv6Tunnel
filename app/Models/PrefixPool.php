<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrefixPool extends Model
{
    protected $table = 'prefix_pool';

    protected $fillable = [
        'tunnel_server_id',
        'address',
        'cidr',
    ];

    protected $hidden = [
        'tunnel_server_id',
    ];

    public function subPrefixes()
    {
        return $this->hasMany(TunnelPrefix::class);
    }

    public function getRoutedSubPrefixesAttribute()
    {
        return $this->subPrefixes()->where('routed_prefix', true)->get();
    }

    public function server()
    {
        return $this->belongsTo(TunnelServer::class, 'tunnel_server_id');
    }

    public function getPrefixAttribute()
    {
        return $this->address . '/' . $this->cidr;
    }

}
