<?php

use App\Models\TunnelServer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTunnelServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tunnel_servers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('address');
            $table->timestamps();
        });

        $tunnelServer = new TunnelServer();
        $tunnelServer->name = 'nz-01-tunnel-server';
        $tunnelServer->address = '185.121.168.253';
        $tunnelServer->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tunnel_servers');
    }
}
