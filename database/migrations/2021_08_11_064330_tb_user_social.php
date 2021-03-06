<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbUserSocial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_user_social', function (Blueprint $table) {
            $table->id();
            $table->string('social_id');
            $table->string('nama');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('platform');
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
        Schema::connection('mysql')->dropIfExists('tb_user_social');
    }
}
