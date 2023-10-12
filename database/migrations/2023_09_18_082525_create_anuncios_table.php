<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnunciosTable extends Migration
{
    public function up()
    {
        Schema::create('anuncios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->integer('marca_id')->unsigned();
            $table->integer('modelo_id')->unsigned();
            $table->integer('numero_cliques');
            $table->integer('anunciante_id')->unsigned();
            $table->integer('categoria_id')->unsigned();
            $table->string('data_inicio', 20);
            $table->string('data_fim', 20);
            $table->integer('ordenacao');
            $table->integer('status_publicacao');
            $table->integer('status_pagamento');
            $table->integer('tipo');
            $table->boolean('vendido')->default(0);
            $table->boolean('vitrine')->default(0);
            $table->boolean('destaque_busca')->default(0);
            $table->integer('estado_id');
            $table->integer('cidade_id');
            $table->string('empresa', 100)->nullable();
            $table->integer('tipo_preco');
            $table->string('valor_preco', 100)->nullable();
            $table->string('descricao', 100);
            $table->string('foto1')->nullable();
            $table->string('foto2')->nullable();
            $table->string('foto3')->nullable();
            $table->string('foto4')->nullable();
            $table->string('foto5')->nullable();
            $table->string('foto6')->nullable();
            $table->string('foto7')->nullable();
            $table->string('foto8')->nullable();
            $table->string('foto9')->nullable();
            $table->string('foto10')->nullable();
            $table->timestamps();

            $table->foreign('anunciante_id')->references('id')->on('anunciantes');
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('marca_id')->references('id')->on('marcas');
            $table->foreign('modelo_id')->references('id')->on('modelos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('anuncios');
    }
}
