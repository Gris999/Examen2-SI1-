<?php

namespace App\Modules\AsignacionHorarios\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\GestionAcademica\Models\Grupo;
use App\Modules\GestionAcademica\Models\Aula;

class Horario extends Model
{
    protected $table = 'horarios';
    protected $primaryKey = 'id_horario';
    public $timestamps = false;

    protected $fillable = [
        'id_docente_materia_gestion', 'id_grupo', 'id_aula', 'dia', 'hora_inicio', 'hora_fin',
        'modalidad', 'virtual_plataforma', 'virtual_enlace', 'observacion',
    ];

    public function asignacion()
    {
        return $this->belongsTo(DocenteMateriaGestion::class, 'id_docente_materia_gestion', 'id_docente_materia_gestion');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula', 'id_aula');
    }
}

