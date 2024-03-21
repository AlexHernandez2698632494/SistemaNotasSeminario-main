<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudiantesReprobados extends Model
{
    use HasFactory;
    protected $table = 'estudiantesreprobados';
    protected $primaryKey = 'idDetalle';
    public $timestamps = false;

    protected $fillable = [
        'idEstudiante',  
        'idGrupo',        
        'promedio', 
        'actividadReposicion',
        'descripcion',
        'porcentaje',
        'nota',
        'porcentajeGanado',
        'estadoReprobado'               
    ];
}
