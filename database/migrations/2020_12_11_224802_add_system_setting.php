<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSystemSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_setting', function (Blueprint $table) {
            $table->id();
            $table->string('default_code')->comment('預設代理碼')->nullable();
            $table->string('android_game_download_url')->comment('android下載')->nullable();
            $table->string('ios_game_download_url')->comment('ios下載')->nullable();
            $table->string('download_teach_url')->comment('下載教學')->nullable();
            $table->string('activity_url')->comment('活動網址')->nullable();
            $table->string('activity_file_url')->comment('活動圖片')->nullable();
            $table->string('marquee_word')->comment('跑馬燈')->nullable();
            $table->timestamps();
        });

        $date = date('Y-m-d H:i:s');

        DB::table('system_setting')->insert([
            'created_at' => $date,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_setting');
    }
}
