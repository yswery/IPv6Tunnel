<?php

namespace App\Console\Commands;

use App\Models\Tunnel;
use App\Models\TunnelServer;
use App\Services\TunnelService;
use Illuminate\Console\Command;

class ReconfigureTunnelsCommand extends Command
{
    protected $tunnelService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zIPv6Tunnel:reconfigure-tunnels {tunnel-server-name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconfigure all tunnels on tunnel server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->tunnelService = new TunnelService();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tunnelServerName = $this->argument('tunnel-server-name');

        if (empty($tunnelServerName) === true) {
            $this->warn('Missing: "tunnel-server-name" argument, example:');
            $this->comment('------------------------');
            foreach (TunnelServer::all() as $tunnelServer) {
                $this->info('* ' . $tunnelServer->name);
            }
            $this->comment('------------------------');
            return;
        }

        $tunnelServer = TunnelServer::where('name', $tunnelServerName)->first();

        $tunnels = Tunnel::whereNotNull('user_id')
            ->whereNotNull('remote_v4_address')
            ->where('tunnel_server_id', $tunnelServer->id)
            ->get();

        if ($tunnels->count() < 1) {
            $this->warn('No tunnels found on ' . $tunnelServerName);
            return;
        }

        // Loop through tunnels to reconfigure them
        $sshCommands = [];
        foreach ($tunnels as $tunnel) {

            // Remove all prefixes
            foreach ($tunnel->prefixes as $tunnelPrefix) {
                $sshCommands[] = 'ip route del ' . $tunnelPrefix->address . '/' . $tunnelPrefix->cidr . ' dev ' . $tunnel->local_interface;
            }

            // Remove the tunnel
            $sshCommands[] = 'ip addr del ' . $tunnel->local_tunnel_address . '/64 dev ' . $tunnel->local_interface;
            $sshCommands[] = 'ip link set ' . $tunnel->local_interface . ' down';
            $sshCommands[] = 'ip tunnel del ' . $tunnel->local_interface;

            // Add the tunnel
            $sshCommands[] = 'ip tunnel add ' . $tunnel->local_interface . ' mode sit remote ' . $tunnel->remote_v4_address . ' local ' . $tunnel->local_v4_address . ' ttl 255';
            $sshCommands[] = 'ip link set ' . $tunnel->local_interface . ' up';
            $sshCommands[] = 'ip link set dev ' . $tunnel->local_interface . ' mtu ' . $tunnel->mtu_size;
            $sshCommands[] = 'ip addr add ' . $tunnel->local_tunnel_address . '/64 dev ' . $tunnel->local_interface;

            // Add all the prefixes
            foreach ($tunnel->prefixes as $tunnelPrefix) {
                $sshCommands[] = 'ip route add ' . $tunnelPrefix->address . '/' . $tunnelPrefix->cidr . ' dev ' . $tunnel->local_interface;
            }
        }

        // Run all the commands as a single link command
        dump($sshCommands);
        $sshCommandString = '';
        foreach ($sshCommands as $sshCommand) {
            $sshCommandString .= $sshCommand . '; ';
        }
        \SSH::into($tunnelServerName)->run($sshCommandString);

    }
}
