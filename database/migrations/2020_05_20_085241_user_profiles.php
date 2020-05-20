<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname',32)->nullable(false)->comment("名称");
            $table->tinyInteger('sex')->nullable(false)->comment("性别 0-未知 1-男 2-女");
            $table->string('language',8)->nullable(false)->comment("语言");
            $table->string('city',255)->nullable(false)->comment("城市");
            $table->string('country',255)->nullable(false)->comment("国家");
            $table->string('province',255)->nullable(false)->comment("省");
            $table->string('headimgurl', 255)->nullable(false)->comment("头像");
            $table->string('phone', 32)->nullable(false)->comment("手机号");
            $table->integer('channel_id')->nullable(false)->comment("渠道号");
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
        Schema::dropIfExists('user_profiles');
    }
}
