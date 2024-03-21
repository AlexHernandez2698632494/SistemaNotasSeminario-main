<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grupos extends Model
{
    use HasFactory;

    protected $table = 'grupo';
    protected $primaryKey = 'idGrupo';
    public $timestamps = false;

    // public function materia(): BelongsTo
    // {
    //     return $this->belongsTo(Materias::class);
    // }
}
