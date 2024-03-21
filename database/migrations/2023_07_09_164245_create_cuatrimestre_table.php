<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuatrimestreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuatrimestre', function (Blueprint $table) {
            $table->integer('idCuatrimestre')->primary();
            $table->string('nombreCuatrimestre', 200)->nullable();
            $table->integer('idEtapa')->nullable();
            
            $table->foreign('idEtapa', 'cuatrimestre_ibfk_1')->references('idEtapa')->on('etapa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuatrimestre');
    }
}
