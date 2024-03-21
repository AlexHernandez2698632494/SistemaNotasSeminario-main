<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Ciclos extends Model
{
    use HasFactory;

    protected $table = 'ciclo';
    protected $primaryKey = 'idCiclo';
    public $timestamps = false;

    // public function materiasGrupo(): HasManyThrough
    // {
    //     return $this->hasManyThrough(Materias::class, Grupos::class);
    // }
}
