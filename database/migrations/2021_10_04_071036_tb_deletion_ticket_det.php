<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbDeletionTicketDet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('tb_deletion_ticket_det', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_deletion_ticket')->constrained('tb_deletion_ticket');
            $table->string('status');
            $table->date('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('tb_deletion_ticket_det');
    }
}
