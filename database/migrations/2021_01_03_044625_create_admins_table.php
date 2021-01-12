<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name')->nullable();
            $table->string('roles')->nullable();
            $table->string('introduction')->nullable();
            $table->string('avatar')->default('https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif')->comment('头像');
            $table->string('phone')->nullable();
            $table->tinyInteger('status')->default(1)->comment('状态 ，1 正常，2 禁用');
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
        Schema::dropIfExists('admins');
    }
}
