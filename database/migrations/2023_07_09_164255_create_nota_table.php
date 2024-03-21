<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nota', function (Blueprint $table) {
            $table->integer('idNota')->primary();
            $table->integer('idGrupo')->nullable();
            $table->integer('idEstudiante')->nullable();
            $table->integer('idEvaluacion')->nullable();
            $table->float('nota')->nullable();
            
            $table->foreign('idGrupo', 'nota_ibfk_1')->references('idGrupo')->on('grupo');
            $table->foreign('idEstudiante', 'nota_ibfk_2')->references('idEstudiante')->on('estudiante');
            $table->foreign('idEvaluacion', 'nota_ibfk_3')->references('idEvaluacion')->on('evaluacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nota');
    }
}
