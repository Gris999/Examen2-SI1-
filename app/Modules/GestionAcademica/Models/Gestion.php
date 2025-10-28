<?php

namespace App\Modules\GestionAcademica\Models;

use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    protected $table = 'gestiones';
    protected $primaryKey = 'id_gestion';
    public $timestamps = false;

    protected $fillable = ['codigo', 'descripcion', 'fecha_inicio', 'fecha_fin', 'activo'];

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'id_gestion', 'id_gestion');
    }
}

