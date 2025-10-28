<?php

namespace App\Modules\GestionAcademica\Models;

use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    protected $table = 'facultades';
    protected $primaryKey = 'id_facultad';
    public $timestamps = false;

    protected $fillable = ['nombre', 'sigla', 'descripcion'];

    public function carreras()
    {
        return $this->hasMany(Carrera::class, 'id_facultad', 'id_facultad');
    }
}

