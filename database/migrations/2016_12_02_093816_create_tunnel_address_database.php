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
        for ($i = 0; $i < 100   0; $i++) {
            $baseAddress = $baseIpSapce . str_pad($i, 4, '0', STR_PAD_LEFT);

            $tunnelAddress                 = new TunnelAddress();
            $tunnelAddress->tunnel_address = $baseAddress;
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
