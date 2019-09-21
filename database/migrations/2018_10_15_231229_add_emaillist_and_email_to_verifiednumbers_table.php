<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmaillistAndEmailToVerifiednumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verifiednumbers', function (Blueprint $table) {
            //
            $table->string('email')->nullable(true)->default(null);
            $table->string('emaillist')->nullable(true)->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verifiednumbers', function (Blueprint $table) {
            //
            $table->dropcolumn('email');
            $table->dropcolumn('emaillist');
        });
    }
}
