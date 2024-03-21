<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docente', function (Blueprint $table) {
            $table->integer('idDocente')->primary();
            $table->string('nombreDocente', 250)->nullable();
            $table->string('apellidoDocente', 250)->nullable();
            $table->string('duiDocente', 11)->nullable();
            $table->string('numeroTelefono', 9)->nullable();
            $table->text('correoDocente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docente');
    }
}
