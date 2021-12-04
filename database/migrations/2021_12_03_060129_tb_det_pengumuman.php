<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbDetPengumuman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_det_pengumuman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengumuman')->nullable()->constrained('tb_pengumuman');
            $table->string('link');
            $table->string('status');
            $table->datetime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('tb_det_pengumuman');
    }
}
