<?php

use App\Models\TunnelAddress;
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
        Schema::create('tunnel_addresses', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->nullable()->index();

            $table->string('tunnel_address');
            $table->string('remote_address')->nullable();
            $table->string('local_address')->nullable();
            $table->string('local_interface')->nullable();

            $table->timestamps();
        });

        // Seed the initial db
        // This address will be used for the 1 <-> 1 tunnel
        $baseIpSapce = '2a06:1280:1bce::';
        for ($i = 0; $i < 1000; $i++) {
            $trailingNumber = str_pad($i, 4, '0', STR_PAD_LEFT);
            $baseAddress    = $baseIpSapce . $trailingNumber;

            $tunnelAddress                  = new TunnelAddress();
            $tunnelAddress->tunnel_address  = $baseAddress;
            $tunnelAddress->local_address   = '185.121.168.253'; // Local address of the tunnel server
            $tunnelAddress->local_interface = 'Ipv6Tunnel' . $trailingNumber;
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
        Schema::drop('tunnel_addresses');
    }
}
