<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiantes extends Model
{
    use HasFactory;
    
    protected $table = 'estudiante';
    protected $primaryKey = 'idEstudiante';
    public $timestamps = false;
}
