<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransaksiPeminjamanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi_peminjaman', function (Blueprint $table) {
            // add foreign key
            $table->foreign('peminjam')->references('id')->on('anggota')->onUpdate('cascade');
            $table->foreign('buku')->references('id')->on('buku')->onUpdate('cascade');
            $table->foreign('pemberi_pinjaman')->references('id')->on('admin')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_peminjaman', function (Blueprint $table) {
            //
        });
    }
}
