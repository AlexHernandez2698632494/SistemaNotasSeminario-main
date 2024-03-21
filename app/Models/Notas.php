<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notas extends Model
{
    use HasFactory;
    protected $table = 'nota';
    protected $primaryKey = 'idNota';
    public $timestamps = false;

    protected $fillable = [
        'idGrupo',  
        'idEstudiante',
        'idEvaluacion',
        'nota',
        'porcentajeGanado',        
    ];
}
