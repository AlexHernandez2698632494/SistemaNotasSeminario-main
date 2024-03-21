<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo', function (Blueprint $table) {
            $table->integer('idGrupo')->primary();
            $table->integer('idMateria')->nullable();
            $table->string('nombreGrupo', 500)->nullable();
            $table->date('anio')->nullable();
            $table->integer('idDocente')->nullable();
            $table->integer('idCiclo')->nullable();
            $table->integer('estadoFinalizacion')->default(1)->comment("1-activo, 0-finalizado");
            
            $table->foreign('idMateria', 'grupo_ibfk_1')->references('idMateria')->on('materia');
            $table->foreign('idDocente', 'grupo_ibfk_2')->references('idDocente')->on('docente');
            $table->foreign('idCiclo', 'grupo_ibfk_3')->references('idCiclo')->on('ciclo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupo');
    }
}
