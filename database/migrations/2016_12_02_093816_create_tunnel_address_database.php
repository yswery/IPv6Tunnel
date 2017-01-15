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

            $table->integer('tunnel_server_id')->index();
            $table->integer('user_id')->nullable()->index();

            $table->string('local_tunnel_address');
            $table->string('remote_tunnel_address');
            $table->string('remote_v4_address')->nullable();
            $table->string('local_v4_address');
            $table->string('local_interface');

            $table->integer('mtu_size')->default(1450);

            $table->timestamps();
        });
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
