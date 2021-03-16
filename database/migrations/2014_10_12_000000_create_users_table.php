<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        $date = date('Y-m-d H:i:s');

        DB::table('admin_users')->insert([
            'name' => 'admin',
            'account' => 'admin',
            'password' => Hash::make('password'),
            'created_at' => $date,
            'updated_at' => $date
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
