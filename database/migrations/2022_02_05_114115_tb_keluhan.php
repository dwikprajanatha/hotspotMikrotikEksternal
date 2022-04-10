<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbKeluhan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_keluhan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nik_id')->nullable()->constrained('tb_nik');
            $table->string('nama');
            $table->string('isi');
            $table->string('read')->default(0);
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
        Schema::connection('mysql')->dropIfExists('tb_keluhan');
    }
}
