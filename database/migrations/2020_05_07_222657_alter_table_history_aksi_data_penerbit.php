<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableHistoryAksiDataPenerbit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_aksi_data_penerbit_buku', function (Blueprint $table) {
            // add foreign key
            $table->foreign('penerbit')->references('id')->on('penerbit_buku')->onUpdate('cascade');
            $table->foreign('pembuat')->references('id')->on('admin')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_aksi_data_penerbit_buku', function (Blueprint $table) {
            //
        });
    }
}
