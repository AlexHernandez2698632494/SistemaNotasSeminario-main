<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCicloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciclo', function (Blueprint $table) {
            $table->integer('idCiclo')->primary();
            $table->string('nombreCiclo', 200)->nullable();
            $table->date('fechaInicio')->nullable();
            $table->date('fechaFinalizacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciclo');
    }
}
