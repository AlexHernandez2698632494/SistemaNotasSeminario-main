<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleestudiantegrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalleestudiantegrupo', function (Blueprint $table) {
            $table->integer('idDetalle')->primary();
            $table->integer('idEstudiante')->nullable();
            $table->integer('idGrupo')->nullable();
            
            $table->foreign('idEstudiante', 'detalleestudiantegrupo_ibfk_1')->references('idEstudiante')->on('estudiante');
            $table->foreign('idGrupo', 'detalleestudiantegrupo_ibfk_2')->references('idGrupo')->on('grupo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalleestudiantegrupo');
    }
}
