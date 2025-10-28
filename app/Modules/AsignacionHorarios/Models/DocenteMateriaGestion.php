<?php

namespace App\Modules\AsignacionHorarios\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Docente;
use App\Modules\GestionAcademica\Models\Materia;
use App\Modules\GestionAcademica\Models\Gestion;

class DocenteMateriaGestion extends Model
{
    protected $table = 'docente_materia_gestion';
    protected $primaryKey = 'id_docente_materia_gestion';
    public $timestamps = false;

    protected $fillable = [
        'id_docente', 'id_materia', 'id_gestion', 'fecha_asignacion', 'estado',
        'aprobado_por', 'aprobado_en', 'activo',
    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia', 'id_materia');
    }

    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'id_gestion', 'id_gestion');
    }
}

