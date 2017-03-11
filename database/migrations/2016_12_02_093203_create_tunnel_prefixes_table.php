<?php

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
            $table->integer('prefix_pool_id')->index();
            $table->integer('tunnel_server_id')->index();

            $table->string('address');
            $table->integer('cidr');
            $table->decimal('ip_dec_start', 39, 0)->unsigned()->index();
            $table->decimal('ip_dec_end', 39, 0)->unsigned()->index();

            $table->string('name');
            $table->string('country_code', 2);

            $table->boolean('routed_prefix')->default(true);

            $table->text('dns_servers_json')->nullable();

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
        Schema::drop('tunnel_prefixes');
    }
}
