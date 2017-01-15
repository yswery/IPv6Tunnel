<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefixPoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prefix_pool', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('tunnel_server_id')->index();
            $table->string('address');
            $table->integer('cidr');
            $table->string('name');
            $table->string('country_code', 2);
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
        Schema::drop('prefix_pool');
    }
}
