<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWebUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('phone', 100);
            $table->string('vx', 100);
            $table->integer('status')->default(0)->comment('0: 未聯絡, 1:已連絡, 2:來亂的');
            $table->text('msg')->nullable();
            $table->string('ip')->nullable()->comment('ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_users');
    }
}
