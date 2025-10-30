<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;

    protected $table = 'gestiones';
    protected $primaryKey = 'id_gestion';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'activo',
    ];
}

