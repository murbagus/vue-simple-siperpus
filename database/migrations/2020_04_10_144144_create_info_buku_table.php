<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoBukuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_buku', function (Blueprint $table) {
            $table->string('isbn', 25)->primary();
            $table->string('judul', 150);
            $table->string('pengarang', 100);
            $table->unsignedBigInteger('penerbit');
            $table->string('klasifikasi', 25);
            $table->unsignedInteger('tahun_terbit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('info_buku');
    }
}
