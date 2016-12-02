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

}
