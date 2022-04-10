<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbDetLoadBalancing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_det_load_balancing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_load_balancing')->nullable()->constrained('tb_load_balancing');
            $table->string('interface');
            $table->string('ip');
            $table->string('gateway');
            $table->string('dns');
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
        Schema::connection('mysql')->dropIfExists('tb_det_load_balancing');
    }
}
