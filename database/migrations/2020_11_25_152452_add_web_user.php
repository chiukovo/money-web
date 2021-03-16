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
            $table->string('account', 100);
            $table->string('password', 100);
            $table->string('real_password', 100);
            $table->string('phone', 100);
            $table->string('email', 100)->nullable();
            $table->string('bankcard_name')->nullable()->comment('銀行戶名');
            $table->string('bankcard_account')->nullable()->comment('銀行帳號');
            $table->string('name')->nullable()->comment('銀行戶名');
            $table->string('nickname', 100)->nullable()->comment('暱稱');
            $table->string('code')->nullable()->comment('推廣代碼');
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
