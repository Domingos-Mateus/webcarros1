<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoAnunciantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_anunciantes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plano_anunciante_id')->unsigned();
            $table->timestamps();

            $table->foreign('plano_anunciante_id')->references('id')->on('planos_anunciantes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historico_anunciantes');
    }
}
