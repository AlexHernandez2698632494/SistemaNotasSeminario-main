<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadesExtraordinaria extends Model
{
    use HasFactory;
    protected $table = 'actividadesextraordinarias';
    protected $primaryKey = 'idActividad';
    public $timestamps = false;

    protected $fillable = [
        'idDetalle',  
        'actividad',
        'descripcion',
        'porcentaje',
        'nota',
        'porcentajeGanado',        
    ];
}
