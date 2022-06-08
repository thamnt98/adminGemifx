<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMt5UrlTableMt5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mt5_connect', function (Blueprint $table) {
            $table->string('mt5_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mt5_connect', function (Blueprint $table) {
            $table->dropColumn('mt5_url');
        });
    }
}
