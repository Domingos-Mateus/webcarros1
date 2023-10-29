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
            $table->string('responsavel', 50);
            $table->string('email', 50);
            $table->string('telefone', 50);
            $table->string('cpf', 50);
            $table->string('cep', 50);
            $table->string('foto', 100)->nullable();
            $table->integer('plano_id')->nullable();
            $table->integer('estado_id');
            $table->integer('cidade_id');
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
        Schema::dropIfExists('anunciantes');
    }
}
