<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materias extends Model
{
    use HasFactory;

    protected $table = 'materia';
    protected $primarykey = 'idMateria';
    public $timestamps = false;

    // public function grupo(): HasMany
    // {
    //     return $this->hasMany(Grupos::class);
    // }
}
