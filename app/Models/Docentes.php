<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Docentes extends Model
{
    use HasFactory;

    protected $table = 'docente';
    protected $primaryKey = 'idDocente';
    public $timestamps = false;

    //Se define la relación, un docente puede impartir varías materias
    public function materias(): HasMany 
    {
        return $this->hasMany(MateriasDocente::class);
    }
}
