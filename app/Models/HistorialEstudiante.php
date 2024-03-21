<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialEstudiante extends Model
{
    use HasFactory;

    protected $table = 'historialestudiante';
    protected $primaryKey = 'idHistorial';
    public $timestamps = false;

    protected $fillable = [
        'idEstudiante',  
        'idMateria',
        'anio',
        'promedio', 
        'convocatoria'               
    ];
}
