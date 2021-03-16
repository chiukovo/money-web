<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingDisablePhone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('system_setting', function (Blueprint $table) {
            $table->text('disabled_phone')->nullable()->comment('禁用手機,隔開');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_setting', function ($table) {
            $table->dropColumn('disabled_phone');
        });
    }
}
