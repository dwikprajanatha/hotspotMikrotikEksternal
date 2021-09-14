<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbDeletionTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_deletion_ticket', function (Blueprint $table) {
            $table->id();
            $table->string('ticket')->unique();
            $table->string('status');
            $table->string('platform');
            $table->date('created_at');
            $table->date('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('tb_deletion_ticket');
    }
}
