<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableHistoryAksiDataPenerbitBuku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_aksi_data_penerbit_buku', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penerbit');
            $table->string('pembuat', 15);
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
        Schema::dropIfExists('history_aksi_data_penerbit_buku');
    }
}
