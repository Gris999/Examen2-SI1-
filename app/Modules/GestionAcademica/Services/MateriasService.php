<?php

namespace App\Modules\GestionAcademica\Services;

use App\Modules\GestionAcademica\Models\Materia;

/** Servicio simple para Materias (borrador). */
class MateriasService
{
    public function listarPorCarrera(int $idCarrera)
    {
        return Materia::where('id_carrera', $idCarrera)->get();
    }
}

