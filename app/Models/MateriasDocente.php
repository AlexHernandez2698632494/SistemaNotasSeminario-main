<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriasDocente extends Model
{
    use HasFactory;
    protected $table = 'materiasdocente';
    protected $primaryKey = 'idDetalle';
    public $timestamps = false;
}
