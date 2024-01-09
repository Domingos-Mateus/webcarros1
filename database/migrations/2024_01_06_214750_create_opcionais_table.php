<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opcionais', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('categoria_opcional_id')->unsigned();
            $table->string('nome',50);
            $table->timestamps();

            $table->foreign('categoria_opcional_id')->references('id')->on('categoria_opcionais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opcionais');
    }
}
