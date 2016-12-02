<?php

use App\Models\Tunnel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTunnelAddressDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tunnels', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->nullable()->index();

            $table->string('local_tunnel_address');
            $table->string('remote_tunnel_address');
            $table->string('remote_v4_address')->nullable();
            $table->string('local_v4_address')->nullable();
            $table->string('local_interface')->nullable();

            $table->timestamps();
        });

        // Seed the initial db
        // This address will be used for the 1 <-> 1 tunnel
        $baseIpSapce = '2a06:1280:1bce::';
        for ($i = 0; $i < 1000; $i++) {
            $trailingNumber = str_pad($i, 4, '0', STR_PAD_LEFT);
            $baseAddress    = $baseIpSapce . $trailingNumber;

            $tunnelAddress                        = new Tunnel();
            $tunnelAddress->local_tunnel_address  = $baseAddress . ':aaaa';
            $tunnelAddress->remote_tunnel_address = $baseAddress . ':bbbb';
            $tunnelAddress->local_v4_address      = '185.121.168.253'; // Local address of the tunnel server
            $tunnelAddress->local_interface       = 'Ipv6Tunnel' . $trailingNumber;
            $tunnelAddress->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tunnels');
    }
}
