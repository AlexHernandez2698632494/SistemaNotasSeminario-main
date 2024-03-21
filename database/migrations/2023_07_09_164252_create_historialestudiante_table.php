<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialestudianteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historialestudiante', function (Blueprint $table) {
            $table->integer('idHistorial')->primary();
            $table->integer('idEstudiante')->nullable();
            $table->integer('idMateria')->nullable();
            $table->integer('idCiclo')->nullable();
            $table->float('promedio')->nullable();
            
            $table->foreign('idEstudiante', 'historialestudiante_ibfk_1')->references('idEstudiante')->on('estudiante');
            $table->foreign('idMateria', 'historialestudiante_ibfk_2')->references('idMateria')->on('materia');
            $table->foreign('idCiclo', 'historialestudiante_ibfk_3')->references('idCiclo')->on('ciclo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historialestudiante');
    }
}
