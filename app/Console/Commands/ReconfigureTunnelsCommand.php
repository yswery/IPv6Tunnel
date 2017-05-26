<?php

namespace App\Console\Commands;

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

        if (is_null($tunnelServer) === true) {
            $this->warn('No tunnel server found on by name ' . $tunnelServerName);
            return;
        }

        $this->info('Attempting to reprovision ' . $tunnelServer->tunnels->count() . ' tunnels');
        $this->tunnelService->reprovisionTunnelServer($tunnelServer);
    }
}
