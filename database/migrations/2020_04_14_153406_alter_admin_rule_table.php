<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAdminRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_rule', function (Blueprint $table) {
            // add foreign key
            $table->foreign('admin')->references('id')->on('admin');
            $table->foreign('rule')->references('id')->on('rule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_rule', function (Blueprint $table) {
            //
        });
    }
}
