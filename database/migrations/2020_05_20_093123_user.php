<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class User extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 64)->nullable(false)->comment("用户名");
            $table->string('password', 32)->nullable(false)->comment("密码,md5");
            $table->string('open_id',64)->nullable(false)->comment("微信开放ID");
            $table->string('union_id',64)->nullable(false)->comment("微信唯一ID");
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
        Schema::dropIfExists('user');
    }
}
