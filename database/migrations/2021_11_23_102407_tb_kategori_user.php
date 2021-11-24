<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbKategoriUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_kategori_user', function (Blueprint $table) {
            $table->id();
            $table->string('group');
            $table->string('rx_rate');
            $table->string('tx_rate');
            $table->string('min_rx_rate');
            $table->string('min_tx_rate');
            $table->string('priority');
            $table->string('idle_timeout');
            $table->string('session_timeout');
            $table->string('port_limit');
            $table->string('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('tb_kategori_user');
    }
}
