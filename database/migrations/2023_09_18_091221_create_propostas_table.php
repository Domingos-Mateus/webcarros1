<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propostas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo', 100);
            $table->integer('anuncio_id')->unsigned();
            $table->string('nome', 100);
            $table->string('email', 50);
            $table->string('ddd', 5);
            $table->string('telefone', 20);
            $table->text('mensagem');
            $table->timestamps();

            $table->foreign('anuncio_id')->references('id')->on('anuncios');
        });
    }


    public function down()
    {
        Schema::dropIfExists('propostas');
    }
}
