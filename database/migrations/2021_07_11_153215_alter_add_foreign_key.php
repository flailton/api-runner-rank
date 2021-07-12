<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provas', function (Blueprint $table) {
            $table->foreign('tipo_prova_id')->references('id')->on('tipo_provas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provas', function (Blueprint $table) {
            $table->dropForeign('provas_tipo_prova_id_foreign');
        });
    }
}
