<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 100);
            $table->boolean('activo')->default(1);
            $table->integer('quantidade_anuncios');
            $table->string('valor');
            $table->string('tipo');
            $table->boolean('anuncio_destaque');
            $table->integer('quantidade_anuncio_destaque');
            $table->integer('quantidade_anuncio_vitrine');
            $table->integer('dias_publicacao');
            $table->string('texto_plano', 200);
            $table->integer('quantidade_fotos');
            $table->string('link_pagamento', 100)->nullable();
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
        Schema::dropIfExists('planos');
    }
}
