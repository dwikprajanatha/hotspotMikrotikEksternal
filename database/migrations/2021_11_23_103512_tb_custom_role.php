<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbCustomRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_custom_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user_social')->nullable()->constrained('tb_user_social');
            $table->foreignId('id_user_hotspot')->nullable()->constrained('tb_user_hotspot');
            $table->string('attribute');
            $table->string('value');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('tb_custom_role');
    }
}
