<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbUserHotspot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_user_hotspot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nik_id')->nullable()->constrained('tb_nik');
            $table->string('username');
            // $table->string('password');
            $table->string('kategori');
            $table->string('mac')->nullable();
            $table->string('ip')->nullable();
            $table->date('created_at');
            $table->string('isDeleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('tb_user_hotspot');
    }
}
