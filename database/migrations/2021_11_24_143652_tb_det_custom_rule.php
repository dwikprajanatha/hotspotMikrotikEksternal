<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbDetCustomRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_det_custom_rule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_custom_rule')->nullable()->constrained('tb_custom_rule');
            $table->string('attribute');
            $table->string('op');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('tb_det_custom_rule');
    }
}
