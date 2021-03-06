<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHistoryAksiDataInfoBukuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_aksi_data_info_buku', function (Blueprint $table) {
            // add foreign key
            $table->foreign('isbn')->references('isbn')->on('info_buku')->onUpdate('cascade');
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
        Schema::table('info_buku', function (Blueprint $table) {
            //
        });
    }
}
