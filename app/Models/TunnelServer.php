<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

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

    public function prefixPools()
    {
        return $this->hasMany(PrefixPool::class);
    }

    public function tunnels()
    {
        return $this->hasMany(Tunnel::class);
    }

    public function sshExec($sshCommands)
    {
        $sshCommandString = '';
        if (is_array($sshCommands) === true) {
            foreach ($sshCommands as $sshCommand) {
                $sshCommandString .= $sshCommand . '; ';
            }
        } else {
            $sshCommandString = $sshCommands;
        }

        Config::set('remote.connections.' . $this->name, [
            'host'     => $this->address . ':' . $this->ssh_port,
            'username' => 'root',
            'password' => $this->ssh_password,
            'timeout'  => 5,
        ]);

        $this->output = '';

        try {
            \SSH::into($this->name)->run($sshCommandString, function($line)
            {
                $this->output = $line;
            });
        } catch (\Exception $e) {
            return false;
        }

        return trim($this->output);
    }

}
