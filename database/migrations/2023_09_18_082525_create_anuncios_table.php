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
            $table->string('titulo', 200);
            $table->integer('tipo_veiculo_id')->unsigned();
            $table->integer('tecnologia_id')->unsigned();
            $table->integer('marca_id')->unsigned();
            $table->integer('modelo_id')->unsigned();
            $table->integer('numero_cliques')->default(0);
            $table->integer('numero_cliques_contato')->default(0);
            $table->integer('numero_cliques_mensagem')->default(0);
            $table->integer('situacao_veiculo');
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
            //$table->string('cep', 50);
            //$table->integer('estado_id')->unsigned();
            //$table->integer('cidade_id')->unsigned();
            //$table->string('empresa', 100)->nullable();
            $table->integer('tipo_preco');
            $table->string('valor_preco', 100)->nullable();
            $table->string('mostrar_preco');
            $table->integer('fabricante_id')->unsigned();
            $table->string('ano_fabricacao', 100)->nullable();
            $table->string('ano_modelo', 100)->nullable();
            $table->string('carroceria', 100)->nullable();
            $table->string('estilo', 100)->nullable();
            $table->integer('portas')->nullable();
            $table->integer('cilindros')->nullable();
            $table->float('motor')->nullable();
            $table->integer('cor_id')->unsigned();
            $table->integer('transmissao_id')->unsigned();
            $table->integer('combustivel_id')->unsigned();
            $table->string('placa', 100)->nullable();
            $table->string('km', 100)->nullable();
            $table->string('sinistrado', 100)->nullable();
            //$table->string('conforto_id');
            //$table->string('seguranca_id');
            $table->string('opcionais_id');
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
            $table->foreign('tipo_veiculo_id')->references('id')->on('tipos_veiculos');
            $table->foreign('tecnologia_id')->references('id')->on('tecnologias');
            $table->foreign('cor_id')->references('id')->on('cors');
            //$table->foreign('estado_id')->references('id')->on('estados');
            //$table->foreign('cidade_id')->references('id')->on('cidades');
            $table->foreign('combustivel_id')->references('id')->on('combustivels');
            $table->foreign('transmissao_id')->references('id')->on('transmissaos');
            $table->foreign('fabricante_id')->references('id')->on('fabricantes');
        });
    }

    public function down()
    {
        Schema::dropIfExists('anuncios');
    }
}
