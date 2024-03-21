<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluacion', function (Blueprint $table) {
            $table->integer('idEvaluacion')->primary();
            $table->string('nombreEvaluacion', 500)->nullable();
            $table->integer('idMateria')->nullable();
            $table->date('fechaInicio')->nullable();
            $table->date('fechaFinalizacion')->nullable();
            
            $table->foreign('idMateria', 'evaluacion_ibfk_1')->references('idMateria')->on('materia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluacion');
    }
}
