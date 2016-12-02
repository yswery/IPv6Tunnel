<?php

use App\Models\TunnelPrefix;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTunnelPrefixesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tunnel_prefixes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->nullable()->index();
            $table->integer('tunnel_id')->nullable()->index();

            $table->string('address');
            $table->integer('cidr');

            $table->timestamps();
        });

        // Seed the initial db
        $baseIpSapce = '2a06:1280:2';
        for ($i = 0; $i < 1000; $i++) {
            $baseAddress = $baseIpSapce . str_pad($i, 3, '0', STR_PAD_LEFT) . '::';

            $tunnelPrefix          = new TunnelPrefix();
            $tunnelPrefix->address = $baseAddress;
            $tunnelPrefix->cidr    = 48;
            $tunnelPrefix->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tunnel_prefixes');
    }
}
