<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudianteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudiante', function (Blueprint $table) {
            $table->integer('idEstudiante')->primary();
            $table->string('nombreEstudiante', 500)->nullable();
            $table->string('apellidoEstudiante', 500)->nullable();
            $table->date('fechaNacimiento')->nullable();
            $table->string('duiEstudiante', 10)->nullable();
            $table->date('anioingreso')->nullable();
            $table->float('cum')->nullable();
            $table->date('fechaBautismo')->nullable();
            $table->date('fechaConfirmacion')->nullable();
            $table->string('parroquia', 250)->nullable();
            $table->text('direccion')->nullable();
            $table->string('numeroTelefonicoCasa', 9)->nullable();
            $table->string('numeroMovil', 9)->nullable();
            $table->string('nombrePadre', 250)->nullable();
            $table->string('nombreMadre', 250)->nullable();
            $table->text('enfermedades')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estudiante');
    }
}
