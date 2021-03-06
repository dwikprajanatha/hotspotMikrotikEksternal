<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbDetKategoriUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_det_kategori_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kategori_user')->nullable()->constrained('tb_kategori_user');
            $table->foreignId('id_user_social')->nullable()->constrained('tb_user_social');
            $table->foreignId('id_user_hotspot')->nullable()->constrained('tb_user_hotspot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('tb_det_kategori_user');
    }
}
