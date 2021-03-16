<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSendLogField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('send_log', function (Blueprint $table) {
            $table->integer('status')->default(1)->after('msg')->comment('0: error, 1: success');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('send_log', function ($table) {
            $table->dropColumn('status');
        });
    }
}
