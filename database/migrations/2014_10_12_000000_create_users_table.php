<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('activo')->default(1);
            $table->string('perfil')->default('admin');
            $table->rememberToken();
            $table->timestamps();
        });
    }



    public function down()
    {
        Schema::dropIfExists('users');
    }
}
