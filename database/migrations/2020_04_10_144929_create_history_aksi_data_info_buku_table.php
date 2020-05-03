<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryAksiDataInfoBukuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_aksi_data_info_buku', function (Blueprint $table) {
            $table->id();
            $table->string('isbn', 25);
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
        Schema::dropIfExists('history_data_info_buku');
    }
}
