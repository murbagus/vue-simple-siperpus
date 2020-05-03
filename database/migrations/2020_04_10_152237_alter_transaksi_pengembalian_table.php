<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransaksiPengembalianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi_pengembalian', function (Blueprint $table) {
            // add foreign key
            $table->foreign('peminjaman')->references('id')->on('transaksi_peminjaman')->onUpdate('cascade');
            $table->foreign('penerima')->references('id')->on('admin')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_pengembalian', function (Blueprint $table) {
            //
        });
    }
}
