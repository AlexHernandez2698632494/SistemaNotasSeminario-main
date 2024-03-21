<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleEstudiantesGrupo extends Model
{
    use HasFactory;

    protected $table = 'detalleestudiantegrupo';
    protected $primaryKey = 'idDetalle';
    public $timestamps = false;
}
