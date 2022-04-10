<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbLinkRejected extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_link_rejected', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user_hotspot')->nullable()->constrained('tb_user_hotspot');
            $table->string('link');
            $table->string('token');
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
        Schema::connection('mysql')->dropIfExists('tb_link_rejected');
    }
}
