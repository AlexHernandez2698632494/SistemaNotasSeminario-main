<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTitulosdocenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('titulosdocente', function (Blueprint $table) {
            $table->integer('idDetalleTitulo')->primary();
            $table->integer('idDocente')->nullable();
            $table->string('tituloDocente', 250)->nullable();
            
            $table->foreign('idDocente', 'titulosdocente_ibfk_1')->references('idDocente')->on('docente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('titulosdocente');
    }
}
