<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryAksiDataAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_aksi_data_admin', function (Blueprint $table) {
            $table->id();
            $table->string('admin', 20);
            $table->string('pembuat', 20);
            $table->text('catatan_aksi');
            $table->dateTime('waktu_terjadi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_aksi_data_admin');
    }
}
