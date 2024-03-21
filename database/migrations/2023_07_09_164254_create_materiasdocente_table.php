<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMateriasdocenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materiasdocente', function (Blueprint $table) {
            $table->integer('idDetalle')->primary();
            $table->integer('idDocente')->nullable();
            $table->integer('idMateria')->nullable();
            
            $table->foreign('idDocente', 'materiasdocente_ibfk_1')->references('idDocente')->on('docente');
            $table->foreign('idMateria', 'materiasdocente_ibfk_2')->references('idMateria')->on('materia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materiasdocente');
    }
}
