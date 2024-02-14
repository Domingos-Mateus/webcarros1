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
            $table->string('nome_empresa', 50);
            $table->string('pessoal_responsavel', 50);
            $table->string('tipo_anunciante', 50);
            $table->string('cnpj', 50);
            $table->string('telefone', 50);
            $table->string('celular', 50);
            $table->string('whatsapp', 50);
            $table->string('email', 50)->unique();
            $table->string('password', 50);
            $table->string('foto', 100)->nullable();
            $table->integer('status');
            $table->string('site', 50);
            $table->string('cep', 50);
            $table->string('endereco', 50);
            $table->string('numero', 50);
            $table->string('complemento', 50);
            $table->string('bairro', 50);
            $table->string('endereco_comercial', 50);
            $table->string('numero_comercial', 50);
            $table->string('complemento_comercial', 50);
            $table->string('bairro_comercial', 50);
            $table->string('cep_comercial', 50);
            $table->integer('estado_id')->unsigned();
            $table->integer('cidade_id')->unsigned();
            $table->integer('cidade_comercial_id')->unsigned();
            $table->integer('regiao_id')->unsigned();
            $table->string('observacao');
            $table->integer('usuario_id')->nullable();
            $table->timestamps();


            $table->foreign('estado_id')->references('id')->on('estados');
            $table->foreign('cidade_id')->references('id')->on('cidades');
            $table->foreign('cidade_comercial_id')->references('id')->on('cidades');
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
