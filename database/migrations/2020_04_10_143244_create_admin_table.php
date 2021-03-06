<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->string('id', 15)->primary(); // 20200510001 -> tahun-bulan-masuk/jeniskelamin/indeks(4digit)
            $table->string('password', 250);
            $table->string('nomor_ktp', 20);
            $table->string('nama', 50);
            $table->string('tempat_lahir', 25);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['laki', 'perempuan']);
            $table->text('alamat');
            $table->string('nomor_telpon', 15);
            $table->string('email', 25);
            $table->string('foto', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
