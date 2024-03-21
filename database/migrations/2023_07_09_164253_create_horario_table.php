<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horario', function (Blueprint $table) {
            $table->integer('idHorario')->primary();
            $table->time('horaInicio')->nullable();
            $table->time('horaFinalizacion')->nullable();
            $table->integer('idGrupo')->nullable();
            
            $table->foreign('idGrupo', 'horario_ibfk_1')->references('idGrupo')->on('grupo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horario');
    }
}
