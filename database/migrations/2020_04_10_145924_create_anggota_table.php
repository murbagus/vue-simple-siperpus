<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('nama', 50);
            $table->string('tempat_lahir', 25);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['laki', 'perempuan']);
            $table->text('alamat');
            $table->string('nomor_telpon', 15);
            $table->string('email', 25);
            $table->string('foto', 50);
            $table->string('nama_sekolah', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggota');
    }
}
