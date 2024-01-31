<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanosAnunciantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planos_anunciantes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plano_id')->unsigned();
            $table->integer('anunciante_id')->unsigned();
            $table->string('status', 50);
            $table->date('data_vencimento');
            $table->timestamps();

            $table->foreign('plano_id')->references('id')->on('planos');
            $table->foreign('anunciante_id')->references('id')->on('anunciantes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planos_anunciantes');
    }
}
