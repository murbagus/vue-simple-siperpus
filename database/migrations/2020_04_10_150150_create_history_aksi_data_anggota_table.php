<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryAksiDataAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_aksi_data_anggota', function (Blueprint $table) {
            $table->id();
            $table->string('anggota', 30);
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
        Schema::dropIfExists('history_data_anggota');
    }
}
