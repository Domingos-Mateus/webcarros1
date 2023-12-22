<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnunciantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anunciantes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 50);
            $table->string('tipo', 50);
            $table->string('email', 50);
            $table->string('telefone', 50);
            $table->string('foto', 100)->nullable();
            $table->integer('estado_id')->unsigned();
            $table->integer('cidade_id')->unsigned();
            $table->integer('regiao_id')->unsigned();
            $table->integer('status');
            $table->timestamps();


            $table->foreign('estado_id')->references('id')->on('estados');
            $table->foreign('cidade_id')->references('id')->on('cidades');
            $table->foreign('regiao_id')->references('id')->on('regioes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anunciantes');
    }
}
