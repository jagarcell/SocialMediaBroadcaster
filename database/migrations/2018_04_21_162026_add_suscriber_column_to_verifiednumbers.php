<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSuscriberColumnToVerifiednumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('verifiednumbers', function(Blueprint $table){
            $table->string('suscriber')->nullable(true)->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('verifiednumbers', function(Blueprint $table){
            $table->dropColumn('suscriber');
        });
    }
}
