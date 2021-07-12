<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('corredor_id');
            $table->unsignedBigInteger('prova_id');
            $table->time('tempo_inicio_prova', 0);
            $table->time('tempo_fim_prova', 0);
            $table->timestamps();

            $table->foreign('corredor_id')->references('id')->on('corredores');
            $table->foreign('prova_id')->references('id')->on('provas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resultados');
    }
}
