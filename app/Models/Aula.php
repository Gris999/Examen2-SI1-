<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $table = 'aulas';
    protected $primaryKey = 'id_aula';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'capacidad',
        'ubicacion',
    ];

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'id_aula', 'id_aula');
    }
}

