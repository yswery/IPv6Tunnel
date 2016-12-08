<?php

use Illuminate\Database\Seeder;

class SeedInitialUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user           = new \App\Models\User();
        $user->name     = 'My Home';
        $user->email    = 'home@home.com';
        $user->password = bcrypt('home@home.com');
        $user->save();


        $tunnelServer = \App\Models\TunnelServer::where('name', 'nz-01-tunnel-server')->first();
        $tunnelService = new \App\Services\TunnelService();
        $results = $tunnelService->createTunnelCombo($user, $tunnelServer, '210.54.37.139');
        dump($results);

    }
}
