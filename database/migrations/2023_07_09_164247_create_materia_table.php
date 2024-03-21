<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMateriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materia', function (Blueprint $table) {
            $table->integer('idMateria')->primary();
            $table->string('nombreMateria', 500)->nullable();
            $table->integer('idCuatrimestre')->nullable();
            
            $table->foreign('idCuatrimestre', 'materia_ibfk_1')->references('idCuatrimestre')->on('cuatrimestre');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materia');
    }
}
